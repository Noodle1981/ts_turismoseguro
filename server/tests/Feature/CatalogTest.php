<?php

declare(strict_types=1);

namespace Tests\Feature;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_can_list_departments(): void
    {
        $response = $this->getJson('/api/v1/departments');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug', 'description', 'hero_image', 'is_active', 'properties_count'],
                ],
            ]);

        $this->assertCount(19, $response->json('data'));
    }

    public function test_can_list_published_properties_with_pagination(): void
    {
        $response = $this->getJson('/api/v1/properties');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'title', 'slug', 'property_type', 'location',
                        'price', 'price_type', 'capacity', 'verification_status',
                        'publication_status', 'department', 'images', 'amenities',
                    ],
                ],
                'meta' => ['current_page', 'last_page', 'total'],
            ]);

        $this->assertGreaterThan(0, count($response->json('data')));
    }

    public function test_can_filter_properties_by_department(): void
    {
        $response = $this->getJson('/api/v1/properties?department=rawson');

        $response->assertStatus(200);
        foreach ($response->json('data') as $prop) {
            $this->assertEquals('rawson', $prop['department']['slug']);
        }
    }

    public function test_can_filter_properties_by_max_price(): void
    {
        $response = $this->getJson('/api/v1/properties?max_price=30000');

        $response->assertStatus(200);
        foreach ($response->json('data') as $prop) {
            $this->assertLessThanOrEqual(30000, $prop['price']);
        }
    }

    public function test_can_get_property_detail_by_slug(): void
    {
        $response = $this->getJson('/api/v1/properties/solo-dia-quinta-los-olivos');

        $response->assertStatus(200)
            ->assertJsonPath('data.slug', 'solo-dia-quinta-los-olivos')
            ->assertJsonPath('data.verification_status', 'verified');
    }

    public function test_returns_404_for_unknown_property_slug(): void
    {
        $response = $this->getJson('/api/v1/properties/propiedad-fantasma-inexistente');

        $response->assertStatus(404);
    }

    public function test_can_get_property_availability(): void
    {
        $properties = $this->getJson('/api/v1/properties')->json('data');
        $propertyId = $properties[0]['id'];

        $response = $this->getJson("/api/v1/properties/{$propertyId}/availability");

        $response->assertStatus(200)
            ->assertJsonStructure(['data']);
    }

    public function test_can_list_experiences(): void
    {
        $response = $this->getJson('/api/v1/experiences');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id', 'title', 'slug', 'category', 'main_location',
                        'price_per_person', 'duration', 'operator_name', 'is_verified',
                    ],
                ],
            ]);

        $this->assertGreaterThan(0, count($response->json('data')));
    }

    public function test_can_get_experience_detail_by_slug(): void
    {
        $response = $this->getJson('/api/v1/experiences/tour-de-las-estrellas-en-el-leoncito');

        $response->assertStatus(200)
            ->assertJsonPath('data.slug', 'tour-de-las-estrellas-en-el-leoncito')
            ->assertJsonPath('data.is_verified', true);
    }
}
