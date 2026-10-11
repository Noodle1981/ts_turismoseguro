<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Property;
use App\Models\User;
use App\Models\VerificationRequest;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PropertyAndVerificationSeeder extends Seeder
{
    /**
     * Seeder canónico alineado con ValidadorBandejaView.vue y PropietarioPanelView.vue.
     * Todas las propiedades pertenecen a anfitrion@turismoseguro.com.ar.
     */
    public function run(): void
    {
        $anfitrion = User::where('email', 'anfitrion@turismoseguro.com.ar')->first()
            ?? User::where('role', 'client')->first()
            ?? User::first();

        $validador = User::where('email', 'validador@turismoseguro.com.ar')->first()
            ?? User::where('role', 'validator')->first()
            ?? User::first();

        $supervisor = User::where('email', 'supervisor@turismoseguro.com.ar')->first();

        $rawson = Department::where('slug', 'rawson')->first() ?? Department::where('name', 'Rawson')->first();
        $pocito = Department::where('slug', 'pocito')->first() ?? Department::where('name', 'Pocito')->first();
        $rivadavia = Department::where('slug', 'rivadavia')->first() ?? Department::where('name', 'Rivadavia')->first();
        $zonda = Department::where('slug', 'zonda')->first() ?? Department::where('name', 'Zonda')->first();

        $dataset = [
            // 1. SOL-2025-1042: Quinta El Remanso del Médano (Estado: PENDIENTE / URGENTE)
            [
                'request_code' => 'SOL-2025-1042',
                'title' => 'Quinta El Remanso del Médano',
                'department_id' => $rawson?->id,
                'property_type' => 'Quinta con Pileta',
                'location' => 'Médano de Oro, Rawson',
                'address' => 'Calle 5 y Ramón Franco, Médano de Oro',
                'price' => 45000,
                'price_type' => 'por día',
                'capacity' => 35,
                'bedrooms' => 1,
                'bathrooms' => 2,
                'description' => 'Hermosa quinta diurna en Médano de Oro con quincho cerrado para 35 personas, pileta con cerco reglamentario, 2 freezers y parrillero techado. Solicitud de inspección inicial para verificación presencial.',
                'host_whatsapp' => '+54 9 264 458-9211',
                'host_phone' => '+54 9 264 458-9211',
                'latitude' => -31.6241000,
                'longitude' => -68.4982000,
                'featured' => false,
                'verification_status' => 'pending',
                'publication_status' => 'draft',
                'verified_at' => null,
                'certificate_number' => null,
                'primary_image' => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&q=80',
                'amenities' => ['Pileta con cerco seguro', 'Quincho 35 pax', '2 Freezers', 'Parrillero techado'],
                'request_status' => 'pending',
                'request_notes' => 'Solicitó inspección preferencial para habilitación de fin de semana.',
                'checklist' => [
                    'cerco_pileta' => true,
                    'disyuntor' => true,
                    'matafuegos' => false,
                    'cbu_verificado' => true,
                ],
                'visit_date' => Carbon::now()->addDays(2)->toDateString(),
            ],

            // 2. SOL-2025-0988: Finca Los Olivos Diurnos (Estado: EN PROCESO / VISITA HOY)
            [
                'request_code' => 'SOL-2025-0988',
                'title' => 'Finca Los Olivos Diurnos',
                'department_id' => $pocito?->id,
                'property_type' => 'Quinta con Pileta',
                'location' => 'Pocito Sur, San Juan',
                'address' => 'Calle 12 y Vidart, Pocito Sur',
                'price' => 52000,
                'price_type' => 'por día',
                'capacity' => 40,
                'bedrooms' => 1,
                'bathrooms' => 2,
                'description' => 'Finca campestre en Pocito Sur con amplio parque arbolado, galería para 40 asistentes, cancha de fútbol 5, pileta con cerco y parrillero doble. Proceso de inspección in situ en curso.',
                'host_whatsapp' => '+54 9 264 492-3344',
                'host_phone' => '+54 9 264 492-3344',
                'latitude' => -31.6812000,
                'longitude' => -68.5521000,
                'featured' => false,
                'verification_status' => 'under_review',
                'publication_status' => 'draft',
                'verified_at' => null,
                'certificate_number' => 'Acta #988-POC/26',
                'primary_image' => 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=800&q=80',
                'amenities' => ['Pileta de diseño', 'Galería para 40 pers.', 'Cancha de fútbol 5', 'Parrilla'],
                'request_status' => 'in_progress',
                'request_notes' => 'Verificar traba del cerco perimetral y constatar titularidad de cuenta bancaria.',
                'checklist' => [
                    'cerco_pileta' => true,
                    'disyuntor' => true,
                    'matafuegos' => true,
                    'cbu_verificado' => true,
                ],
                'visit_date' => Carbon::today()->toDateString(),
            ],

            // 3. SOL-2025-0849: Solo Día Quinta Los Olivos / Quinta Don Antonio (Estado: VALIDADO / APROBADO)
            [
                'request_code' => 'SOL-2025-0849',
                'title' => 'Solo Día Quinta Los Olivos',
                'department_id' => $rawson?->id,
                'property_type' => 'Quinta con Piscina',
                'location' => 'Médano de Oro, Rawson',
                'address' => 'Calle Picasso 1400, Médano de Oro',
                'price' => 45000,
                'price_type' => 'por día',
                'capacity' => 45,
                'bedrooms' => 1,
                'bathrooms' => 3,
                'description' => 'Predio diurno homologado por Turismo Seguro San Juan. Quincho cerrado para 45 personas, pileta cercada de 8x4m, cancha de vóley, 2 freezers y amplio estacionamiento interno sombreado.',
                'host_whatsapp' => '+54 9 264 458-9211',
                'host_phone' => '+54 9 264 458-9211',
                'latitude' => -31.6155000,
                'longitude' => -68.4890000,
                'featured' => true,
                'verification_status' => 'verified',
                'publication_status' => 'published',
                'verified_at' => Carbon::now()->subDays(15),
                'certificate_number' => 'Acta #849-MED/26',
                'primary_image' => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=800&q=80',
                'amenities' => ['Pileta cercada', 'Quincho 45 personas', 'Cancha de vóley', 'Estacionamiento'],
                'request_status' => 'approved',
                'request_notes' => 'Propiedad con Sello San Juan Verificado activo y QR de autenticidad emitido.',
                'checklist' => [
                    'cerco_pileta' => true,
                    'disyuntor' => true,
                    'matafuegos' => true,
                    'cbu_verificado' => true,
                ],
                'visit_date' => Carbon::now()->subDays(15)->toDateString(),
            ],

            // 4. SOL-2025-0812: Quincho San Cayetano (Estado: RECHAZADO / OBSERVADO)
            [
                'request_code' => 'SOL-2025-0812',
                'title' => 'Quincho San Cayetano',
                'department_id' => $rivadavia?->id,
                'property_type' => 'Quincho para Eventos',
                'location' => 'La Bebida, Rivadavia',
                'address' => 'Calle Comercio s/n, La Bebida',
                'price' => 35000,
                'price_type' => 'por día',
                'capacity' => 20,
                'bedrooms' => 0,
                'bathrooms' => 1,
                'description' => 'Quincho para encuentros familiares diurnos. Posee pileta descubierta y parrillero. En proceso de adecuación de medidas de seguridad obligatorias.',
                'host_whatsapp' => '+54 9 264 471-0023',
                'host_phone' => '+54 9 264 471-0023',
                'latitude' => -31.5450000,
                'longitude' => -68.6120000,
                'featured' => false,
                'verification_status' => 'rejected',
                'publication_status' => 'draft',
                'verified_at' => null,
                'certificate_number' => null,
                'primary_image' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=800&q=80',
                'amenities' => ['Pileta abierta', 'Quincho 20 pax', 'Parrilla'],
                'request_status' => 'rejected',
                'request_notes' => 'CBU informado no coincide con el DNI del titular. En proceso de rectificación de documentación.',
                'checklist' => [
                    'cerco_pileta' => false,
                    'disyuntor' => true,
                    'matafuegos' => false,
                    'cbu_verificado' => false,
                ],
                'visit_date' => Carbon::now()->subDays(5)->toDateString(),
            ],

            // 5. Cabaña & Posada El Refugio (Zonda) - Verificada
            [
                'request_code' => 'SOL-2025-0792',
                'title' => 'Cabaña & Posada El Refugio de los Olivos',
                'department_id' => $zonda?->id,
                'property_type' => 'Cabaña',
                'location' => 'Zonda, San Juan',
                'address' => 'Ruta Provincial 12 km 18, Zonda',
                'price' => 42000,
                'price_type' => 'por día',
                'capacity' => 25,
                'bedrooms' => 2,
                'bathrooms' => 2,
                'description' => 'Hermosa cabaña y parque arbolado al pie de la precordillera en Zonda. Piscina cercada con solárium, parrillero techado y vistas panorámicas. Homologada por Turismo Seguro.',
                'host_whatsapp' => '+54 9 264 411-2233',
                'host_phone' => '+54 9 264 411-2233',
                'latitude' => -31.5510000,
                'longitude' => -68.7180000,
                'featured' => true,
                'verification_status' => 'verified',
                'publication_status' => 'published',
                'verified_at' => Carbon::now()->subDays(20),
                'certificate_number' => 'Acta #792-ZON/26',
                'primary_image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAm0w3Eo3bIt_7_3405BlGYRthz836ni90tEsKwGbg4jcxaXwi7z66yjMC9regCjEdZzHZ32e2nsRjCv5T8LNXjcr6fhViHukkw4RNlK1SkQ7iWFEUSaW6eR68N6uggcTmSJ1HyFp7q8nY7XpEI4bZOFIhcjvkuleFCt1Z6fEXD2OcOLe7I7hpWifF13iUYWVSSNihJPiUKDBd3DDsVkzgtW9NqCeERC3L1gRdArXuRl_vi_XCT6ofS',
                'amenities' => ['Piscina', 'Parrilla', 'WiFi', 'Cochera Cubierta', 'Vistas a la Montaña'],
                'request_status' => 'approved',
                'request_notes' => 'Inspección técnica anual completada sin observaciones.',
                'checklist' => [
                    'cerco_pileta' => true,
                    'disyuntor' => true,
                    'matafuegos' => true,
                    'cbu_verificado' => true,
                ],
                'visit_date' => Carbon::now()->subDays(20)->toDateString(),
            ],
        ];

        foreach ($dataset as $data) {
            $slug = Str::slug($data['title']);

            $property = Property::updateOrCreate(
                ['slug' => $slug],
                [
                    'user_id' => $anfitrion->id,
                    'department_id' => $data['department_id'],
                    'validator_id' => $validador->id,
                    'title' => $data['title'],
                    'property_type' => $data['property_type'],
                    'location' => $data['location'],
                    'address' => $data['address'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'price_type' => $data['price_type'],
                    'capacity' => $data['capacity'],
                    'bedrooms' => $data['bedrooms'],
                    'bathrooms' => $data['bathrooms'],
                    'host_whatsapp' => $data['host_whatsapp'],
                    'host_phone' => $data['host_phone'],
                    'verification_status' => $data['verification_status'],
                    'publication_status' => $data['publication_status'],
                    'verified_at' => $data['verified_at'],
                    'verification_certificate_number' => $data['certificate_number'],
                    'verified_facade_image' => $data['primary_image'],
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'featured' => $data['featured'],
                ]
            );

            // Imagen principal
            $property->images()->updateOrCreate(
                ['image_path' => $data['primary_image']],
                ['is_primary' => true, 'sort_order' => 1, 'caption' => $property->title]
            );

            // Amenities
            foreach ($data['amenities'] as $amenity) {
                $property->amenities()->firstOrCreate(['amenity' => $amenity]);
            }

            // Solicitud de verificación vinculada (para la bandeja del validador)
            VerificationRequest::updateOrCreate(
                [
                    'property_id' => $property->id,
                    'client_id' => $anfitrion->id,
                ],
                [
                    'validator_id' => $validador->id,
                    'supervisor_id' => $supervisor?->id,
                    'status' => $data['request_status'],
                    'rejection_reason' => $data['request_status'] === 'rejected' ? $data['request_notes'] : null,
                    'visit_date' => $data['visit_date'],
                    'checklist' => $data['checklist'],
                    'notes' => $data['request_notes'],
                    'gps_latitude' => $data['latitude'],
                    'gps_longitude' => $data['longitude'],
                ]
            );
        }
    }
}
