<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Property;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LegacyPropertySeeder extends Seeder
{
    /**
     * Seed properties from legacy database with authentic photos.
     */
    public function run(): void
    {
        $client = User::where('role', 'client')->first() ?? User::first();
        $validator = User::where('role', 'validator')->first() ?? User::first();

        $rawson = Department::where('slug', 'rawson')->first();
        $zonda = Department::where('slug', 'zonda')->first();
        $santaLucia = Department::where('slug', 'santa-lucia')->first();
        $calingasta = Department::where('slug', 'calingasta')->first();

        $properties = [
            [
                'title' => 'Refréscate en el Medanito de Rawson',
                'department_id' => $rawson?->id,
                'property_type' => 'Quinta con Piscina',
                'location' => 'Rawson, San Juan',
                'address' => 'Calle El Medanito s/n',
                'price' => 25000,
                'price_type' => 'por día',
                'capacity' => 15,
                'bedrooms' => 0,
                'bathrooms' => 2,
                'description' => 'Disfruta de un día de pileta y relax en esta hermosa quinta ubicada en El Medanito, Rawson. Ideal para familias y grupos de amigos. Cuenta con una amplia piscina, quincho con parrilla, y todas las comodidades para pasar un día inolvidable. Fácil acceso y total privacidad.',
                'host_whatsapp' => '+54 9 264 456-7890',
                'host_phone' => '264-4567890',
                'certificate_number' => 'TS-RAW-2026-081',
                'featured' => true,
                'primary_image' => 'storage/img/propiedades/medanopileta.jpg',
                'amenities' => ['Piscina', 'Parrilla', 'WiFi', 'Estacionamiento', 'Baños equipados'],
                'rules' => ['No se permiten mascotas.', 'Horario de uso de 10:00 a 20:00.', 'Música a volumen moderado.'],
            ],
            [
                'title' => 'La Posada del Angel',
                'department_id' => $zonda?->id,
                'property_type' => 'Cabaña',
                'location' => 'Zonda, San Juan',
                'address' => 'Ruta Provincial 12 km 18',
                'price' => 35000,
                'price_type' => '/noche',
                'capacity' => 6,
                'bedrooms' => 3,
                'bathrooms' => 2,
                'description' => 'Encantadora posada en el corazón de Zonda, rodeada de montañas y viñedos. Ofrece un ambiente tranquilo y acogedor, perfecto para una escapada romántica o unas vacaciones en familia. Disfruta de nuestras cómodas habitaciones, piscina y vistas espectaculares.',
                'host_whatsapp' => '+54 9 264 411-2233',
                'host_phone' => '264-4112233',
                'certificate_number' => 'TS-ZON-2026-014',
                'featured' => true,
                'primary_image' => 'storage/img/propiedades/laposadadelangel.jpg',
                'amenities' => ['Piscina', 'Parrilla', 'WiFi', 'Cochera', 'Aire Acondicionado', 'Vistas a la Montaña'],
                'rules' => ['Respetar horarios de descanso nocturno.', 'Prohibido fumar en interiores.'],
            ],
            [
                'title' => 'Casona la Robertita',
                'department_id' => $santaLucia?->id,
                'property_type' => 'Casa',
                'location' => 'Santa Lucía, San Juan',
                'address' => 'Calle Balcarce y Pellegrini',
                'price' => 60000,
                'price_type' => '/noche',
                'capacity' => 10,
                'bedrooms' => 4,
                'bathrooms' => 3,
                'description' => 'Amplia casona con gran parque y piscina, ideal para grandes familias o grupos. Ubicada en una zona tranquila de Santa Lucía, ofrece todas las comodidades para una estadía confortable y divertida. Cuenta con múltiples habitaciones, quincho cerrado y un hermoso jardín.',
                'host_whatsapp' => '+54 9 264 488-9900',
                'host_phone' => null,
                'certificate_number' => 'TS-SLU-2026-092',
                'featured' => true,
                'primary_image' => 'storage/img/propiedades/CasonalaRobertita.jpg',
                'amenities' => ['Piscina Privada', 'Parrilla Grande', 'WiFi de Alta Velocidad', 'Cochera Cubierta', 'Jardín Amplio', 'TV Cable'],
                'rules' => ['No se permiten fiestas ni eventos sin autorización previa.', 'Respetar horarios de descanso.'],
            ],
            [
                'title' => 'Cabaña Acogedora en Calingasta',
                'department_id' => $calingasta?->id,
                'property_type' => 'Cabaña',
                'location' => 'Barreal, Calingasta, San Juan',
                'address' => 'Calle Los Enamorados s/n',
                'price' => 20000,
                'price_type' => '/noche',
                'capacity' => 2,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'description' => 'Romántica cabaña de montaña en Barreal, con vista directa a la Cordillera de Ansilta. Calefacción a leña, entorno natural silvestre y cielo diáfano.',
                'host_whatsapp' => '+54 9 264 477-1122',
                'host_phone' => null,
                'certificate_number' => 'TS-CAL-2026-003',
                'featured' => false,
                'primary_image' => 'storage/img/departamentos/barreal.jpg',
                'amenities' => ['Parrilla', 'Vistas a la Montaña', 'WiFi', 'Cochera'],
                'rules' => ['No fumar en interiores.'],
            ],
            [
                'title' => 'Departamento Práctico en Rawson',
                'department_id' => $rawson?->id,
                'property_type' => 'Departamento',
                'location' => 'Rawson, San Juan',
                'address' => 'Av. España y República del Líbano',
                'price' => 18000,
                'price_type' => '/noche',
                'capacity' => 3,
                'bedrooms' => 1,
                'bathrooms' => 1,
                'description' => 'Depto cómodo y totalmente equipado para estancias de trabajo o turismo. Ubicación estratégica con acceso rápido a paradas de colectivo y comercios.',
                'host_whatsapp' => '+54 9 264 499-3344',
                'host_phone' => null,
                'certificate_number' => 'TS-RAW-2026-105',
                'featured' => false,
                'primary_image' => 'storage/img/departamentos/rawson.jpg',
                'amenities' => ['WiFi', 'Aire Acondicionado', 'TV Cable'],
                'rules' => ['No se permiten fiestas.'],
            ],
        ];

        foreach ($properties as $propData) {
            $amenities = $propData['amenities'];
            $rules = $propData['rules'];
            $primaryImage = $propData['primary_image'];
            $certificateNumber = $propData['certificate_number'];
            unset($propData['amenities'], $propData['rules'], $propData['primary_image'], $propData['certificate_number']);

            $slug = Str::slug($propData['title']);

            $property = Property::updateOrCreate(
                ['slug' => $slug],
                array_merge($propData, [
                    'user_id' => $client->id,
                    'validator_id' => $validator->id,
                    'verification_status' => 'verified',
                    'publication_status' => 'published',
                    'verified_at' => now()->subDays(rand(5, 60)),
                    'verification_certificate_number' => $certificateNumber,
                    'verified_facade_image' => $primaryImage,
                ])
            );

            // Guardar imagen principal
            $property->images()->updateOrCreate(
                ['image_path' => $primaryImage],
                ['is_primary' => true, 'sort_order' => 1, 'caption' => $property->title]
            );

            // Guardar comodidades
            foreach ($amenities as $amenity) {
                $property->amenities()->firstOrCreate(['amenity' => $amenity]);
            }

            // Guardar reglas
            foreach ($rules as $rule) {
                $property->rules()->firstOrCreate(['rule' => $rule]);
            }

            // Simular algunas fechas ocupadas en el calendario
            $property->availabilities()->firstOrCreate(
                ['date' => Carbon::today()->addDays(5)->toDateString()],
                ['status' => 'booked', 'notes' => 'Reserva verificada']
            );
            $property->availabilities()->firstOrCreate(
                ['date' => Carbon::today()->addDays(6)->toDateString()],
                ['status' => 'booked', 'notes' => 'Reserva verificada']
            );
        }
    }
}
