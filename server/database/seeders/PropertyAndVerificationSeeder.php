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
            // Propiedad Canónica Oficial Única: Solo Día Quinta Los Olivos
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
                'amenities' => ['Pileta cercada', 'Quincho 45 personas', 'Cancha de vóley', 'Estacionamiento', 'Parrillero techado', '2 Freezers'],
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
