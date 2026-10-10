<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@turismoseguro.com.ar',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'token',
                'user' => [
                    'id',
                    'name',
                    'email',
                    'role',
                    'is_local_resident',
                ],
            ])
            ->assertJson([
                'user' => [
                    'email' => 'admin@turismoseguro.com.ar',
                    'role' => 'super_admin',
                ],
            ]);
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'admin@turismoseguro.com.ar',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_can_register_as_rent_with_geographic_provenance(): void
    {
        $payload = [
            'name' => 'Turista Cordobés',
            'email' => 'turista.cordoba@gmail.com',
            'password' => 'segura1234',
            'password_confirmation' => 'segura1234',
            'phone' => '+54 9 351 999-8888',
            'is_local_resident' => false,
            'origin_country' => 'Argentina',
            'origin_province' => 'Córdoba',
            'origin_city' => 'Villa Carlos Paz',
        ];

        $response = $this->postJson('/api/v1/auth/register/rent', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'token', 'user'])
            ->assertJson([
                'user' => [
                    'email' => 'turista.cordoba@gmail.com',
                    'role' => 'rent',
                    'is_local_resident' => false,
                    'origin_province' => 'Córdoba',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'turista.cordoba@gmail.com',
            'role' => 'rent',
            'origin_city' => 'Villa Carlos Paz',
        ]);
    }

    public function test_host_can_register_as_client(): void
    {
        $payload = [
            'name' => 'Roberto Dueño Cabaña',
            'email' => 'roberto.anfitrion@gmail.com',
            'password' => 'segura1234',
            'password_confirmation' => 'segura1234',
            'phone' => '+54 9 264 555-1234',
        ];

        $response = $this->postJson('/api/v1/auth/register/client', $payload);

        $response->assertStatus(201)
            ->assertJsonStructure(['message', 'token', 'user'])
            ->assertJson([
                'user' => [
                    'email' => 'roberto.anfitrion@gmail.com',
                    'role' => 'client',
                ],
            ]);
    }

    public function test_authenticated_user_can_fetch_profile(): void
    {
        $user = User::where('role', 'client')->first();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/v1/auth/me');

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'role' => 'client',
                ],
            ]);
    }

    public function test_user_can_logout_and_revoke_token(): void
    {
        $user = User::where('role', 'rent')->first();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/v1/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Sesión cerrada correctamente.']);

        $this->assertCount(0, $user->tokens);
    }

    public function test_password_reset_flow(): void
    {
        $user = User::where('role', 'rent')->first();

        // 1. Solicitar recuperación
        $forgotResponse = $this->postJson('/api/v1/auth/forgot-password', [
            'email' => $user->email,
        ]);
        $forgotResponse->assertStatus(200);

        // 2. Simular token en base de datos
        $plainToken = 'token-secreto-12345';
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => Hash::make($plainToken), 'created_at' => now()]
        );

        // 3. Restablecer contraseña
        $resetResponse = $this->postJson('/api/v1/auth/reset-password', [
            'email' => $user->email,
            'token' => $plainToken,
            'password' => 'nuevaPassword123',
            'password_confirmation' => 'nuevaPassword123',
        ]);
        $resetResponse->assertStatus(200);

        // 4. Probar login con nueva clave
        $loginResponse = $this->postJson('/api/v1/auth/login', [
            'email' => $user->email,
            'password' => 'nuevaPassword123',
        ]);
        $loginResponse->assertStatus(200);
    }
}
