<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Property;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyModelTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    public function test_only_published_and_verified_properties_appear_in_published_scope(): void
    {
        $department = Department::first();
        $user = User::first();

        // Propiedad verificada y publicada (visible)
        $published = Property::create([
            'user_id' => $user->id,
            'department_id' => $department->id,
            'title' => 'Cabaña Visible Test',
            'slug' => 'cabana-visible-test',
            'property_type' => 'Cabaña',
            'location' => 'Zonda, San Juan',
            'description' => 'Descripción de prueba',
            'price' => 30000,
            'price_type' => '/noche',
            'capacity' => 4,
            'verification_status' => 'verified',
            'publication_status' => 'published',
        ]);

        // Propiedad en borrador / pendiente de validación (no visible al público)
        $draft = Property::create([
            'user_id' => $user->id,
            'department_id' => $department->id,
            'title' => 'Cabaña Borrador Test',
            'slug' => 'cabana-borrador-test',
            'property_type' => 'Cabaña',
            'location' => 'Zonda, San Juan',
            'description' => 'Descripción borrador',
            'price' => 30000,
            'price_type' => '/noche',
            'capacity' => 4,
            'verification_status' => 'pending',
            'publication_status' => 'draft',
        ]);

        $publishedList = Property::published()->get();

        $this->assertTrue($publishedList->contains($published));
        $this->assertFalse($publishedList->contains($draft));
    }

    public function test_property_relations_work_properly(): void
    {
        $property = Property::with(['department', 'user', 'amenities', 'images', 'rules'])->first();

        $this->assertNotNull($property);
        $this->assertNotNull($property->department);
        $this->assertNotNull($property->user);
        $this->assertGreaterThan(0, $property->amenities->count());
        $this->assertGreaterThan(0, $property->images->count());
    }
}
