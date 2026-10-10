<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained('departments')->restrictOnDelete();
            $table->foreignId('validator_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('title', 200);
            $table->string('slug', 200)->unique()->index();
            $table->string('property_type', 50)->default('Cabaña')->index();
            $table->string('location', 200);
            $table->string('address', 255)->nullable();
            $table->text('description');

            $table->unsignedInteger('price');
            $table->string('price_type', 50)->default('/noche');
            $table->unsignedSmallInteger('capacity');
            $table->unsignedSmallInteger('bedrooms')->default(0);
            $table->unsignedSmallInteger('bathrooms')->default(1);

            $table->string('host_whatsapp', 50)->nullable();
            $table->string('host_phone', 50)->nullable();

            $table->string('verification_status', 30)->default('pending')->index();
            $table->string('publication_status', 30)->default('draft')->index();
            $table->timestamp('verified_at')->nullable();
            $table->string('verification_certificate_number', 50)->nullable();
            $table->string('verified_facade_image', 255)->nullable();

            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->boolean('featured')->default(false)->index();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['publication_status', 'verification_status'], 'idx_properties_published_verified');
            $table->index(['department_id', 'publication_status'], 'idx_properties_dept_published');
        });

        // Imágenes de la propiedad
        Schema::create('property_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('image_path', 255);
            $table->string('caption', 150)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // Comodidades (WiFi, Piscina, Parrilla, etc.)
        Schema::create('property_amenities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('amenity', 100);
            $table->timestamps();

            $table->unique(['property_id', 'amenity']);
        });

        // Reglas de la casa / estancia
        Schema::create('property_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('rule', 255);
            $table->timestamps();
        });

        // Calendario de disponibilidad / reservas bloqueadas
        Schema::create('property_availabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->date('date')->index();
            $table->string('status', 30)->default('booked'); // booked, blocked, maintenance
            $table->string('notes', 150)->nullable();
            $table->timestamps();

            $table->unique(['property_id', 'date'], 'uniq_property_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_availabilities');
        Schema::dropIfExists('property_rules');
        Schema::dropIfExists('property_amenities');
        Schema::dropIfExists('property_images');
        Schema::dropIfExists('properties');
    }
};
