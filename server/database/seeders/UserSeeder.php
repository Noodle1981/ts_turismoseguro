<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed initial users for the 5 roles and geographic provenance.
     */
    public function run(): void
    {
        $defaultPassword = Hash::make('password123');

        $users = [
            [
                'name' => 'Omar Olivera (Super Admin)',
                'email' => 'admin@turismoseguro.com.ar',
                'password' => $defaultPassword,
                'role' => 'super_admin',
                'phone' => '+54 9 264 400-0001',
                'origin_country' => 'Argentina',
                'origin_province' => 'San Juan',
                'origin_city' => 'Capital',
                'is_local_resident' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Supervisor de Calidad TS',
                'email' => 'supervisor@turismoseguro.com.ar',
                'password' => $defaultPassword,
                'role' => 'supervisor',
                'phone' => '+54 9 264 400-0002',
                'origin_country' => 'Argentina',
                'origin_province' => 'San Juan',
                'origin_city' => 'Capital',
                'is_local_resident' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Matías Fernández (Validador Matriculado)',
                'email' => 'validador@turismoseguro.com.ar',
                'password' => $defaultPassword,
                'role' => 'validator',
                'phone' => '+54 9 264 400-0003',
                'origin_country' => 'Argentina',
                'origin_province' => 'San Juan',
                'origin_city' => 'Rawson',
                'is_local_resident' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Elena Morales (Anfitriona Verificada)',
                'email' => 'anfitrion@turismoseguro.com.ar',
                'password' => $defaultPassword,
                'role' => 'client',
                'phone' => '+54 9 264 400-0004',
                'origin_country' => 'Argentina',
                'origin_province' => 'San Juan',
                'origin_city' => 'Zonda',
                'is_local_resident' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Carlos Benítez (Residente Sanjuanino)',
                'email' => 'carlos.sanjuanino@gmail.com',
                'password' => $defaultPassword,
                'role' => 'rent',
                'phone' => '+54 9 264 400-0005',
                'origin_country' => 'Argentina',
                'origin_province' => 'San Juan',
                'origin_city' => 'Rivadavia',
                'is_local_resident' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Lucía Albarracín (Turista Buenos Aires)',
                'email' => 'lucia.turista@gmail.com',
                'password' => $defaultPassword,
                'role' => 'rent',
                'phone' => '+54 9 11 500-0006',
                'origin_country' => 'Argentina',
                'origin_province' => 'Buenos Aires',
                'origin_city' => 'La Plata',
                'is_local_resident' => false,
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }
    }
}
