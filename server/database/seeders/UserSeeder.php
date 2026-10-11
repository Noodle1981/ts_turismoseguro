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
                'name' => 'Administración y Finanzas San Juan',
                'email' => 'administracion@turismoseguro.com.ar',
                'password' => $defaultPassword,
                'role' => 'admin',
                'phone' => '+54 9 264 400-0010',
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
                'dni' => '27.481.930',
                'origin_country' => 'Argentina',
                'origin_province' => 'San Juan',
                'origin_city' => 'Zonda',
                'is_local_resident' => true,
                'is_active' => true,
            ],
            // Inquilinos Canónicos (rol rent)
            [
                'name' => 'Esteban Morales',
                'email' => 'esteban.morales@turismoseguro.com.ar',
                'password' => $defaultPassword,
                'role' => 'rent',
                'phone' => '+54 9 264 458-1290',
                'dni' => '35.889.120',
                'origin_country' => 'Argentina',
                'origin_province' => 'San Juan',
                'origin_city' => 'Capital',
                'is_local_resident' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Carolina Gómez',
                'email' => 'carolina.gomez@turismoseguro.com.ar',
                'password' => $defaultPassword,
                'role' => 'rent',
                'phone' => '+54 9 264 499-8877',
                'dni' => '36.541.902',
                'origin_country' => 'Argentina',
                'origin_province' => 'San Juan',
                'origin_city' => 'Capital',
                'is_local_resident' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Lorena Agüero',
                'email' => 'lorena.aguero@turismoseguro.com.ar',
                'password' => $defaultPassword,
                'role' => 'rent',
                'phone' => '+54 9 264 588-9911',
                'dni' => '32.844.119',
                'origin_country' => 'Argentina',
                'origin_province' => 'San Juan',
                'origin_city' => 'Rivadavia',
                'is_local_resident' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Damián Ruarte',
                'email' => 'damian.ruarte@turismoseguro.com.ar',
                'password' => $defaultPassword,
                'role' => 'rent',
                'phone' => '+54 9 264 588-9922',
                'dni' => '35.109.432',
                'origin_country' => 'Argentina',
                'origin_province' => 'San Juan',
                'origin_city' => 'Rawson',
                'is_local_resident' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Ignacio Castro',
                'email' => 'ignacio.castro@turismoseguro.com.ar',
                'password' => $defaultPassword,
                'role' => 'rent',
                'phone' => '+54 9 264 588-9933',
                'dni' => '28.601.780',
                'origin_country' => 'Argentina',
                'origin_province' => 'San Juan',
                'origin_city' => 'Pocito',
                'is_local_resident' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Beatriz Montiel',
                'email' => 'beatriz.montiel@turismoseguro.com.ar',
                'password' => $defaultPassword,
                'role' => 'rent',
                'phone' => '+54 9 264 588-9944',
                'dni' => '30.984.111',
                'origin_country' => 'Argentina',
                'origin_province' => 'San Juan',
                'origin_city' => 'Santa Lucía',
                'is_local_resident' => true,
                'is_active' => true,
            ],
            [
                'name' => 'Carlos Benítez (Residente Sanjuanino)',
                'email' => 'carlos.sanjuanino@gmail.com',
                'password' => $defaultPassword,
                'role' => 'rent',
                'phone' => '+54 9 264 400-0005',
                'dni' => '34.812.504',
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
                'dni' => '38.990.123',
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
