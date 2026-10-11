<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $events = [
            [
                'slug' => 'fns-2026',
                'title' => 'Fiesta Nacional del Sol (FNS 2026)',
                'description' => 'Mayor celebración cultural y turística de la provincia de San Juan con desfiles, recitales internacionales y feria temática.',
                'category' => 'cultural',
                'start_date' => '2026-11-19',
                'end_date' => '2026-11-22',
                'venue' => 'Predio Costanera Complejo Ferial / Estadio del Bicentenario',
                'impact_departments' => ['Rawson', 'Pocito', 'Capital', 'Chimbas', 'Rivadavia', 'Santa Lucía'],
                'suggested_rate_multiplier' => 1.80,
                'allows_overnight' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'superbike-villicum-2026',
                'title' => 'Superbike & TC en Autódromo El Villicum',
                'description' => 'Competencia automovilística y motociclística de nivel nacional e internacional que atrae a miles de fanáticos y delegaciones.',
                'category' => 'deportivo',
                'start_date' => '2026-10-23',
                'end_date' => '2026-10-25',
                'venue' => 'Circuito San Juan Villicum, Albardón',
                'impact_departments' => ['Albardón', 'Angaco', 'Chimbas', 'Capital', 'San Martín'],
                'suggested_rate_multiplier' => 1.65,
                'allows_overnight' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'navidad-2026',
                'title' => 'Nochebuena & Navidad 2026',
                'description' => 'Celebración festiva de alta demanda para reuniones familiares, asados campestres y estadías de fin de año.',
                'category' => 'fiestas_anuales',
                'start_date' => '2026-12-24',
                'end_date' => '2026-12-25',
                'venue' => 'En toda la provincia de San Juan',
                'impact_departments' => ['TODOS'],
                'suggested_rate_multiplier' => 1.70,
                'allows_overnight' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'ano-nuevo-2027',
                'title' => 'Fin de Año & Año Nuevo 2027',
                'description' => 'Pico máximo de demanda de quintas y cabañas con pileta para festejo de Año Nuevo y estadías con pernocte.',
                'category' => 'fiestas_anuales',
                'start_date' => '2026-12-31',
                'end_date' => '2027-01-01',
                'venue' => 'En toda la provincia de San Juan',
                'impact_departments' => ['TODOS'],
                'suggested_rate_multiplier' => 2.00,
                'allows_overnight' => true,
                'is_active' => true,
            ],
            [
                'slug' => 'expo-minera-2026',
                'title' => 'San Juan Minero: Exposición Internacional',
                'description' => 'Encuentro minero, industrial y corporativo líder de la región cuyana con ejecutivos y técnicos de todo el país.',
                'category' => 'minero_corporativo',
                'start_date' => '2026-11-05',
                'end_date' => '2026-11-07',
                'venue' => 'Predio Cepas Sanjuaninas, Rivadavia',
                'impact_departments' => ['Rivadavia', 'Capital', 'Rawson', 'Pocito', 'Iglesia', 'Calingasta'],
                'suggested_rate_multiplier' => 1.50,
                'allows_overnight' => false,
                'is_active' => true,
            ],
            [
                'slug' => 'safari-sierras-2027',
                'title' => 'Safari Tras las Sierras 2027',
                'description' => 'Clásico festival deportivo y automotor off-road en los ríos secos y quebradas de Valle Fértil.',
                'category' => 'deportivo',
                'start_date' => '2027-02-05',
                'end_date' => '2027-02-08',
                'venue' => 'San Agustín de Valle Fértil',
                'impact_departments' => ['Valle Fértil'],
                'suggested_rate_multiplier' => 1.90,
                'allows_overnight' => true,
                'is_active' => true,
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(['slug' => $event['slug']], $event);
        }
    }
}
