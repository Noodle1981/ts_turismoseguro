<?php

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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category')->default('cultural'); // cultural, deportivo, minero_corporativo, fiestas_anuales
            $table->date('start_date');
            $table->date('end_date');
            $table->string('venue')->nullable();
            $table->json('impact_departments'); // Lista de departamentos beneficiados (ej: ['Rawson', 'Pocito'] o ['TODOS'])
            $table->decimal('suggested_rate_multiplier', 4, 2)->default(1.50); // Multiplicador de tarifa sugerido (ej: 1.50)
            $table->boolean('allows_overnight')->default(true); // Si incentiva pernocte / camas
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['start_date', 'end_date']);
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
