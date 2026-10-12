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
            $table->string('payout_account_type', 30)->nullable()->after('dni'); // cbu, cvu, billetera_virtual
            $table->string('payout_account_number', 50)->nullable()->after('payout_account_type'); // CBU / CVU / Alias / Billetera
            $table->string('payout_bank_name', 100)->nullable()->after('payout_account_number'); // Banco o Proveedor Billetera (Mercado Pago, Banco San Juan, etc.)
            $table->boolean('payout_verified')->default(false)->after('payout_bank_name');
        });

        Schema::table('verification_requests', function (Blueprint $table) {
            $table->string('payout_account_type', 30)->nullable()->after('checklist'); // cbu, cvu, billetera_virtual
            $table->string('payout_account_number', 50)->nullable()->after('payout_account_type');
            $table->string('payout_bank_name', 100)->nullable()->after('payout_account_number');
            $table->boolean('payout_verified')->default(false)->after('payout_bank_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('verification_requests', function (Blueprint $table) {
            $table->dropColumn(['payout_account_type', 'payout_account_number', 'payout_bank_name', 'payout_verified']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['payout_account_type', 'payout_account_number', 'payout_bank_name', 'payout_verified']);
        });
    }
};
