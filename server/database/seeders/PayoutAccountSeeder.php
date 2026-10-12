<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use App\Models\VerificationRequest;
use Illuminate\Database\Seeder;

class PayoutAccountSeeder extends Seeder
{
    public function run(): void
    {
        $anfitrion = User::where('email', 'anfitrion@turismoseguro.com.ar')->first();
        if ($anfitrion) {
            $anfitrion->update([
                'payout_account_type' => 'cbu',
                'payout_account_number' => '04500018-01800045192831',
                'payout_bank_name' => 'Banco San Juan',
                'payout_verified' => true,
            ]);

            // Actualizar todas las solicitudes de Elena Morales
            $updatedCount = VerificationRequest::where('client_id', $anfitrion->id)->update([
                'payout_account_type' => 'cbu',
                'payout_account_number' => '04500018-01800045192831',
                'payout_bank_name' => 'Banco San Juan',
                'payout_verified' => true,
            ]);

            $this->command->info("Anfitrion {$anfitrion->name} (ID {$anfitrion->id}) actualizado con CBU. {$updatedCount} solicitudes actualizadas.");
        }

        $allVrs = VerificationRequest::with('client')->get();
        foreach ($allVrs as $vr) {
            $this->command->line("VR #{$vr->id} (Prop #{$vr->property_id}) Client: {$vr->client?->name} | Type: {$vr->payout_account_type} | Num: {$vr->payout_account_number} | Ver: " . ($vr->payout_verified ? 'true' : 'false'));
        }
    }
}
