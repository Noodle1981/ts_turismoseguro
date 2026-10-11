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
        Schema::table('users', function (Blueprint $table) {
            $table->string('dni', 20)->nullable()->after('phone')->index();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 50)->unique();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Inquilino (rol: rent)
            $table->date('date')->index();
            $table->string('shift', 100)->default('Turno Diurno (10:00 a 20:00 hs)');
            $table->unsignedInteger('guests_count')->default(1);
            $table->unsignedInteger('guests_adults')->default(1);
            $table->unsignedInteger('guests_children')->default(0);
            $table->string('event_type', 150)->nullable();
            $table->string('insurance_code', 100)->nullable(); // Ej: POL-TRIUNFO-9482 / TS-SEG-4821
            $table->string('status', 30)->default('confirmed'); // confirmed, pending, completed, cancelled
            $table->decimal('total_price', 12, 2)->default(0);
            $table->decimal('deposit_paid', 12, 2)->default(0);
            $table->decimal('balance_due', 12, 2)->default(0);
            $table->boolean('cbu_verified')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('dni');
        });
    }
};
