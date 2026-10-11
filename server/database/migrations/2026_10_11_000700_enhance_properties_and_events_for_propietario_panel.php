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
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('locality_id')->nullable()->after('department_id')->constrained('localities')->nullOnDelete();

            // Tarifas multimodales (ARS)
            $table->unsignedInteger('tariff_lunes_jueves')->default(38000)->after('price');
            $table->unsignedInteger('tariff_viernes_domingo')->default(45000)->after('tariff_lunes_jueves');
            $table->unsignedInteger('tariff_sabado_feriados')->default(55000)->after('tariff_viernes_domingo');
            $table->unsignedInteger('tariff_evento_especial')->default(90000)->after('tariff_sabado_feriados');
            $table->unsignedInteger('tariff_pernocte_camas')->nullable()->after('tariff_evento_especial');
            $table->unsignedInteger('tariff_pernocte_carpas')->nullable()->after('tariff_pernocte_camas');
            $table->unsignedInteger('tariff_finde_completo')->nullable()->after('tariff_pernocte_carpas');
            $table->unsignedInteger('tariff_fiesta_nocturna')->nullable()->after('tariff_finde_completo');

            // Franja horaria diurna y días activos
            $table->string('schedule_start', 10)->default('10:00')->after('tariff_fiesta_nocturna');
            $table->string('schedule_end', 10)->default('20:00')->after('schedule_start');
            $table->json('active_days')->nullable()->after('schedule_end'); // [0, 4, 5, 6]

            // Visibilidad y pausa por vacaciones
            $table->boolean('is_listing_visible')->default(true)->after('publication_status')->index();
            $table->string('pause_mode', 20)->nullable()->after('is_listing_visible'); // 'indefinite', 'vacation'
            $table->date('pause_start_date')->nullable()->after('pause_mode');
            $table->date('pause_end_date')->nullable()->after('pause_start_date');

            // Seguro y Póliza
            $table->string('insurance_company', 100)->default('Triunfo Seguros')->after('pause_end_date');
            $table->string('policy_number', 60)->nullable()->after('insurance_company');
            $table->date('policy_valid_until')->nullable()->after('policy_number');
            $table->string('acta_inspeccion_fecha', 60)->nullable()->after('policy_valid_until');
        });

        // Eventos: alcance por departamento y radio
        if (Schema::hasTable('events')) {
            Schema::table('events', function (Blueprint $table) {
                if (!Schema::hasColumn('events', 'radius_km')) {
                    $table->unsignedSmallInteger('radius_km')->default(25)->after('location');
                }
            });

            if (!Schema::hasTable('event_departments')) {
                Schema::create('event_departments', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
                    $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete();
                    $table->timestamps();

                    $table->unique(['event_id', 'department_id']);
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('event_departments')) {
            Schema::dropIfExists('event_departments');
        }

        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['locality_id']);
            $table->dropColumn([
                'locality_id',
                'tariff_lunes_jueves',
                'tariff_viernes_domingo',
                'tariff_sabado_feriados',
                'tariff_evento_especial',
                'tariff_pernocte_camas',
                'tariff_pernocte_carpas',
                'tariff_finde_completo',
                'tariff_fiesta_nocturna',
                'schedule_start',
                'schedule_end',
                'active_days',
                'is_listing_visible',
                'pause_mode',
                'pause_start_date',
                'pause_end_date',
                'insurance_company',
                'policy_number',
                'policy_valid_until',
                'acta_inspeccion_fecha',
            ]);
        });
    }
};
