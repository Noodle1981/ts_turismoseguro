<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Property;
use App\Models\User;
use App\Models\VerificationRequest;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StaffVerificationTest extends TestCase
{
    use RefreshDatabase;

    protected User $supervisorUser;

    protected User $validatorUser;

    protected User $clientUser;

    protected Department $department;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->supervisorUser = User::where('role', 'supervisor')->first()
            ?? User::factory()->create(['role' => 'supervisor', 'email' => 'supervisor.test@sanjuan.gob.ar']);

        $this->validatorUser = User::where('role', 'validator')->first()
            ?? User::factory()->create(['role' => 'validator', 'email' => 'validador.test@sanjuan.gob.ar']);

        $this->clientUser = User::where('role', 'client')->first();
        $this->department = Department::first();
    }

    public function test_supervisor_can_list_all_verification_requests_with_metrics_and_validators(): void
    {
        Sanctum::actingAs($this->supervisorUser);

        $response = $this->getJson('/api/v1/staff/verifications');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'metrics' => ['pending', 'inProgress', 'approved', 'rejected'],
                'available_validators',
            ]);
    }

    public function test_creating_property_creates_pending_verification_request_with_coverage_alert_if_no_local_validator(): void
    {
        Sanctum::actingAs($this->clientUser);

        // Seleccionar o crear un departamento sin validadores
        $emptyDept = Department::where('name', 'Calingasta')->first()
            ?? Department::create([
                'name' => 'Calingasta',
                'slug' => 'calingasta',
                'description' => 'Test sin validadores',
            ]);

        $createResponse = $this->postJson('/api/v1/client/properties', [
            'department_id' => $emptyDept->id,
            'title' => 'Finca Nueva Para Inspección',
            'location' => 'Callejón Las Moras s/n, Calingasta',
            'property_type' => 'quinta',
            'capacity' => 20,
            'observations' => 'Solicitud urgente de prueba',
        ]);

        $createResponse->assertStatus(201);
        $newPropertyId = $createResponse->json('data.id');

        // Verificar que la solicitud existe en DB en estado pending
        $this->assertDatabaseHas('verification_requests', [
            'property_id' => $newPropertyId,
            'status' => 'pending',
            'validator_id' => null,
        ]);

        // Supervisor consulta la bandeja y ve la alerta de cobertura
        Sanctum::actingAs($this->supervisorUser);
        $listResponse = $this->getJson('/api/v1/staff/verifications');

        $listResponse->assertStatus(200);
        $item = collect($listResponse->json('data'))->firstWhere('property_id', $newPropertyId);

        $this->assertNotNull($item);
        $this->assertFalse($item['hasLocalValidator']);
        $this->assertNotNull($item['coverageAlert']);
    }

    public function test_supervisor_can_assign_validator_to_pending_request(): void
    {
        // Crear solicitud pendiente
        $property = Property::create([
            'user_id' => $this->clientUser->id,
            'department_id' => $this->department->id,
            'title' => 'Cabaña Sin Asignar',
            'slug' => 'cabana-sin-asignar',
            'location' => 'Ruta 12 km 18, Zonda',
            'description' => 'Cabaña para fines de semana',
            'price' => 45000,
            'property_type' => 'cabana',
            'capacity' => 10,
            'publication_status' => 'draft',
        ]);

        $vr = VerificationRequest::create([
            'property_id' => $property->id,
            'client_id' => $this->clientUser->id,
            'status' => 'pending',
        ]);

        Sanctum::actingAs($this->supervisorUser);

        $assignResponse = $this->putJson("/api/v1/staff/verifications/{$vr->id}/assign", [
            'validator_id' => $this->validatorUser->id,
            'notes' => 'Comisionado especial por proximidad geográfica.',
        ]);

        $assignResponse->assertStatus(200)
            ->assertJson([
                'verification_request' => [
                    'id' => $vr->id,
                    'status' => 'assigned',
                    'validator_id' => $this->validatorUser->id,
                    'supervisor_id' => $this->supervisorUser->id,
                ],
            ]);

        $this->assertDatabaseHas('verification_requests', [
            'id' => $vr->id,
            'status' => 'assigned',
            'validator_id' => $this->validatorUser->id,
            'supervisor_id' => $this->supervisorUser->id,
        ]);
    }

    public function test_validator_sees_assigned_requests(): void
    {
        $property = Property::create([
            'user_id' => $this->clientUser->id,
            'department_id' => $this->department->id,
            'title' => 'Quinta Asignada a Validador',
            'slug' => 'quinta-asignada-a-validador',
            'location' => 'Calle 5 y Vidart, Pocito',
            'description' => 'Quinta para eventos familiares',
            'price' => 50000,
            'property_type' => 'quinta',
            'capacity' => 15,
            'publication_status' => 'draft',
        ]);

        $vr = VerificationRequest::create([
            'property_id' => $property->id,
            'client_id' => $this->clientUser->id,
            'validator_id' => $this->validatorUser->id,
            'status' => 'assigned',
        ]);

        Sanctum::actingAs($this->validatorUser);

        $response = $this->getJson('/api/v1/staff/verifications?only_my_assignments=true');

        $response->assertStatus(200);
        $ids = collect($response->json('data'))->pluck('id')->all();

        $this->assertContains((string) $vr->id, $ids);
    }
}
