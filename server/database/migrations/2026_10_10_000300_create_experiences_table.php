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
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained('departments')->restrictOnDelete();

            $table->string('title', 200);
            $table->string('slug', 200)->unique()->index();
            $table->string('category', 100)->default('Aventura')->index();
            $table->string('main_location', 200);
            $table->unsignedInteger('price_per_person');
            $table->string('duration', 100);

            $table->text('short_description');
            $table->longText('long_description');

            $table->string('operator_name', 150);
            $table->string('operator_whatsapp', 50);
            $table->string('operator_email', 150)->nullable();

            $table->boolean('is_verified')->default(true)->index();
            $table->boolean('featured')->default(false)->index();

            $table->timestamps();
            $table->softDeletes();
        });

        // Imágenes de experiencias
        Schema::create('experience_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experience_id')->constrained()->cascadeOnDelete();
            $table->string('image_path', 255);
            $table->string('caption', 150)->nullable();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

        // Lo que incluye y no incluye la experiencia
        Schema::create('experience_includes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experience_id')->constrained()->cascadeOnDelete();
            $table->string('item', 255);
            $table->boolean('is_included')->default(true); // true = incluye, false = no incluye
            $table->timestamps();
        });

        // Recomendaciones (abrigo, calzado, etc.)
        Schema::create('experience_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experience_id')->constrained()->cascadeOnDelete();
            $table->string('recommendation', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experience_recommendations');
        Schema::dropIfExists('experience_includes');
        Schema::dropIfExists('experience_images');
        Schema::dropIfExists('experiences');
    }
};
