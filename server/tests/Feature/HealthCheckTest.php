<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class HealthCheckTest extends TestCase
{
    public function test_health_check_endpoint_returns_ok_and_conforms_to_contract(): void
    {
        $response = $this->getJson('/api/v1/health');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'timestamp',
                'environment',
                'services' => [
                    'database',
                    'cache',
                ],
            ])
            ->assertJson([
                'status' => 'ok',
                'services' => [
                    'database' => 'connected',
                    'cache' => 'connected',
                ],
            ]);
    }
}
