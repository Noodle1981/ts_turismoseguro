<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with complete real domain data.
     */
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            LocalitySeeder::class,
            UserSeeder::class,
            PropertyAndVerificationSeeder::class,
            BookingSeeder::class,
            EventSeeder::class,
            LegacyExperienceSeeder::class,
        ]);
    }
}
