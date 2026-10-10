<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Property;
use App\Models\PropertyAvailability;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ClientPropertyTest extends TestCase
{
    use RefreshDatabase;

    protected User $clientUser;

    protected User $otherClientUser;

    protected Department $department;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);

        $this->clientUser = User::where('role', 'client')->first();
        $this->otherClientUser = User::factory()->create([
            'role' => 'client',
            'email' => 'otro.anfitrion@gmail.com',
        ]);
        $this->department = Department::first();
    }

    public function test_client_can_list_only_own_properties(): void
    {
        Sanctum::actingAs($this->clientUser);

        $response = $this->getJson('/api/v1/client/properties');

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);

        foreach ($response->json('data') as $prop) {
            $this->assertEquals($this->clientUser->id, Property::find($prop['id'])->user_id);
        }
    }

    public function test_client_cannot_see_other_clients_property_detail(): void
    {
        // Propiedad que pertenece a $this->clientUser
        $prop = Property::where('user_id', $this->clientUser->id)->first();

        // Autenticado como otro cliente
        Sanctum::actingAs($this->otherClientUser);

        $response = $this->getJson("/api/v1/client/properties/{$prop->id}");

        $response->assertStatus(404);
    }

    public function test_client_can_create_new_property_in_draft_status(): void
    {
        Sanctum::actingAs($this->clientUser);

        $payload = [
            'title' => 'Cabaña Campestre en Barreal',
            'department_id' => $this->department->id,
            'property_type' => 'Cabaña',
            'location' => 'Barreal, Calingasta',
            'address' => 'Calle Los Enamorados s/n',
            'description' => 'Hermosa cabaña con vista directa a la cordillera de Ansilta.',
            'price' => 45000,
            'price_type' => '/noche',
            'capacity' => 6,
            'bedrooms' => 2,
            'bathrooms' => 1,
            'amenities' => ['Parrilla', 'WiFi', 'Cochera'],
            'rules' => ['No fumar adentro'],
        ];

        $response = $this->postJson('/api/v1/client/properties', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('data.title', 'Cabaña Campestre en Barreal')
            ->assertJsonPath('data.publication_status', 'draft') // Nunca autopublicada
            ->assertJsonPath('data.verification_status', 'pending');

        $this->assertDatabaseHas('properties', [
            'title' => 'Cabaña Campestre en Barreal',
            'user_id' => $this->clientUser->id,
            'publication_status' => 'draft',
        ]);
    }

    public function test_client_can_block_and_free_calendar_dates(): void
    {
        Sanctum::actingAs($this->clientUser);
        $prop = Property::where('user_id', $this->clientUser->id)->first();

        // 1. Bloquear rango futuro
        $startDate = now()->addDays(5)->toDateString();
        $endDate = now()->addDays(8)->toDateString();

        $response = $this->postJson("/api/v1/client/properties/{$prop->id}/availability", [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'action' => 'block',
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('property_availabilities', [
            'property_id' => $prop->id,
            'status' => 'blocked',
        ]);

        // 2. Liberar rango
        $freeResponse = $this->postJson("/api/v1/client/properties/{$prop->id}/availability", [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'action' => 'free',
        ]);

        $freeResponse->assertStatus(200);

        $this->assertEquals(0, PropertyAvailability::where('property_id', $prop->id)->whereBetween('date', [$startDate, $endDate])->count());
    }

    public function test_client_can_request_in_situ_verification(): void
    {
        Sanctum::actingAs($this->clientUser);

        // Crear una propiedad borrador
        $prop = Property::create([
            'user_id' => $this->clientUser->id,
            'department_id' => $this->department->id,
            'title' => 'Cabaña de Ensueño en Zonda',
            'slug' => 'cabana-de-ensueno-en-zonda-test',
            'property_type' => 'Cabaña',
            'location' => 'Zonda, San Juan',
            'description' => 'Cabaña con piscina y parrilla en entorno natural.',
            'price' => 38000,
            'price_type' => '/noche',
            'capacity' => 4,
            'verification_status' => 'pending',
            'publication_status' => 'draft',
        ]);

        $response = $this->postJson('/api/v1/client/verifications/request', [
            'property_id' => $prop->id,
            'notes' => 'Tengo disponibilidad para recibir al validador los fines de semana.',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('verification_status', 'pending');

        $this->assertDatabaseHas('verification_requests', [
            'property_id' => $prop->id,
            'client_id' => $this->clientUser->id,
            'status' => 'pending',
        ]);
    }
}
