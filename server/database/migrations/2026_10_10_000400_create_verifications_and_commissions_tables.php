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
        Schema::create('verification_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('validator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('supervisor_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('status', 30)->default('pending')->index(); // pending, assigned, visited, under_supervision, approved, rejected
            $table->text('rejection_reason')->nullable();
            $table->date('visit_date')->nullable();
            $table->json('checklist')->nullable();
            $table->text('notes')->nullable();

            $table->decimal('gps_latitude', 10, 7)->nullable();
            $table->decimal('gps_longitude', 10, 7)->nullable();

            $table->timestamps();
        });

        Schema::create('validator_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('validator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('property_id')->constrained('properties')->cascadeOnDelete();

            $table->unsignedTinyInteger('period_month');
            $table->unsignedSmallInteger('period_year');
            $table->boolean('is_first_month')->default(false);

            $table->unsignedInteger('amount'); // en pesos ARS
            $table->decimal('rate_percentage', 5, 2)->default(15.00); // 70% o 15%
            $table->string('status', 30)->default('pending')->index(); // pending, approved, paid
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            $table->index(['validator_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validator_commissions');
        Schema::dropIfExists('verification_requests');
    }
};
