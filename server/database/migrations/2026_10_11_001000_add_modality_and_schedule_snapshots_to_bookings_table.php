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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('modality', 50)->default('diurno')->after('shift');
            $table->string('check_in_time', 10)->nullable()->after('modality');
            $table->string('check_out_time', 10)->nullable()->after('check_in_time');
            $table->index('modality');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropIndex(['modality']);
            $table->dropColumn(['modality', 'check_in_time', 'check_out_time']);
        });
    }
};
