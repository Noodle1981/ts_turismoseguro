<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Property;
use App\Models\PropertyAvailability;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Seeder con trazabilidad canónica de reservas:
     * - Mes pasado (Septiembre 2026): Alquileres completados (fines de semana + días de semana)
     *   para Quinta Los Olivos y Cabaña & Posada El Refugio.
     * - Mes actual (Octubre 2026): Próximas reservas activas y confirmadas.
     */
    public function run(): void
    {
        $propertyOlivos = Property::where('slug', 'solo-dia-quinta-los-olivos')->first()
            ?? Property::where('title', 'like', '%Los Olivos%')->first();

        $propertyRefugio = Property::where('slug', 'cabana-posada-el-refugio-de-los-olivos')->first()
            ?? Property::where('title', 'like', '%Refugio%')->first();

        $lorena = User::where('email', 'lorena.aguero@turismoseguro.com.ar')->first();
        $damian = User::where('email', 'damian.ruarte@turismoseguro.com.ar')->first();
        $ignacio = User::where('email', 'ignacio.castro@turismoseguro.com.ar')->first();
        $beatriz = User::where('email', 'beatriz.montiel@turismoseguro.com.ar')->first();
        $carolina = User::where('email', 'carolina.gomez@turismoseguro.com.ar')->first();
        $esteban = User::where('email', 'esteban.morales@turismoseguro.com.ar')->first();
        $carlos = User::where('email', 'carlos.sanjuanino@gmail.com')->first();
        $lucia = User::where('email', 'lucia.turista@gmail.com')->first();

        // -------------------------------------------------------------
        // 1. ALQUILERES PASADOS: SEPTIEMBRE 2026 (Completados / Historial)
        // -------------------------------------------------------------
        $septemberBookings = [
            // --- QUINTA LOS OLIVOS (ID 8) ---
            // Sábado 05/Sep - Diurno (Esteban - Buen Inquilino)
            [
                'booking_code' => 'TS-RES-2026-SEP01',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $esteban?->id,
                'date' => '2026-09-05',
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'modality' => 'diurno',
                'check_in_time' => '10:00',
                'check_out_time' => '20:00',
                'guests_count' => 35,
                'guests_adults' => 30,
                'guests_children' => 5,
                'event_type' => 'Cumpleaños 40 de Esteban',
                'insurance_code' => 'TS-SEG-4821',
                'status' => 'completed',
                'cbu_verified' => true,
                'owner_rating' => 'up',
                'owner_observation' => 'Excelente inquilino. Entregó parquizado y pileta impecables.',
                'notes' => 'Alquiler completado sin incidentes.',
            ],
            // Domingo 06/Sep - Diurno (Lorena - Buen Inquilino)
            [
                'booking_code' => 'TS-RES-2026-SEP02',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $lorena?->id,
                'date' => '2026-09-06',
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'modality' => 'diurno',
                'check_in_time' => '10:00',
                'check_out_time' => '20:00',
                'guests_count' => 38,
                'guests_adults' => 28,
                'guests_children' => 10,
                'event_type' => 'Bautismo familiar',
                'insurance_code' => 'POL-TRIUNFO-9482',
                'status' => 'completed',
                'cbu_verified' => true,
                'owner_rating' => 'up',
                'owner_observation' => 'Familia muy respetuosa del predio y de los límites sonoros.',
                'notes' => 'Excelente calificación del anfitrión (5 estrellas).',
            ],
            // Jueves 10/Sep - Diurno (Carlos - Sin Historial / none)
            [
                'booking_code' => 'TS-RES-2026-SEP03',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $carlos?->id,
                'date' => '2026-09-10',
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'modality' => 'diurno',
                'check_in_time' => '10:00',
                'check_out_time' => '20:00',
                'guests_count' => 18,
                'guests_adults' => 18,
                'guests_children' => 0,
                'event_type' => 'Almuerzo de trabajo / Jornada campestre',
                'insurance_code' => 'TS-SEG-4821',
                'status' => 'completed',
                'cbu_verified' => true,
                'owner_rating' => 'none',
                'owner_observation' => null,
                'notes' => 'Tarifa día de semana respetada. Instalaciones en perfecto estado.',
            ],
            // Sábado 12/Sep - Fiesta Nocturna (Damián - Buen Inquilino)
            [
                'booking_code' => 'TS-RES-2026-SEP04',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $damian?->id,
                'date' => '2026-09-12',
                'shift' => 'Fiesta Nocturna (21:00 a 05:00 hs)',
                'modality' => 'fiesta_nocturna',
                'check_in_time' => '21:00',
                'check_out_time' => '05:00',
                'guests_count' => 35,
                'guests_adults' => 30,
                'guests_children' => 5,
                'event_type' => 'Fiesta de 15 años familiar',
                'insurance_code' => 'POL-TRIUNFO-9482',
                'status' => 'completed',
                'cbu_verified' => true,
                'owner_rating' => 'up',
                'owner_observation' => 'Cuidó el parrillero techado y la vajilla. Todo en orden.',
                'notes' => 'Se verificó parrillero techado y cerco perimetral de piscina.',
            ],
            // Domingo 13/Sep - Diurno (Beatriz - Sin Historial / none)
            [
                'booking_code' => 'TS-RES-2026-SEP05',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $beatriz?->id,
                'date' => '2026-09-13',
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'modality' => 'diurno',
                'check_in_time' => '10:00',
                'check_out_time' => '20:00',
                'guests_count' => 32,
                'guests_adults' => 16,
                'guests_children' => 16,
                'event_type' => 'Cumple infantil temático',
                'insurance_code' => 'POL-TRIUNFO-9482',
                'status' => 'completed',
                'cbu_verified' => true,
                'owner_rating' => 'none',
                'owner_observation' => null,
                'notes' => 'Uso recreativo familiar completado exitosamente.',
            ],
            // Miércoles 16/Sep - Diurno (Lucía - Sin Historial)
            [
                'booking_code' => 'TS-RES-2026-SEP06',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $lucia?->id,
                'date' => '2026-09-16',
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'modality' => 'diurno',
                'check_in_time' => '10:00',
                'check_out_time' => '20:00',
                'guests_count' => 14,
                'guests_adults' => 12,
                'guests_children' => 2,
                'event_type' => 'Día de campo turistas',
                'insurance_code' => 'TS-SEG-4821',
                'status' => 'completed',
                'cbu_verified' => true,
                'owner_rating' => 'none',
                'owner_observation' => null,
                'notes' => 'Turistas procedentes de Buenos Aires. Póliza y registro RENAPER OK.',
            ],
            // Sábado 19/Sep - Evento Especial (Ignacio Castro - NO CONFORME / ROJO)
            [
                'booking_code' => 'TS-RES-2026-SEP07',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $ignacio?->id,
                'date' => '2026-09-19',
                'shift' => 'Evento Especial (12:00 a 02:00 hs)',
                'modality' => 'evento_especial',
                'check_in_time' => '12:00',
                'check_out_time' => '02:00',
                'guests_count' => 45,
                'guests_adults' => 40,
                'guests_children' => 5,
                'event_type' => 'Cumpleaños 50 años',
                'insurance_code' => 'TS-SEG-4821',
                'status' => 'completed',
                'cbu_verified' => true,
                'owner_rating' => 'down',
                'owner_observation' => 'Excedió el horario pactado de salida por 2 horas y dejó residuos en la zona de pileta.',
                'notes' => 'Aforo completo 45 personas.',
            ],
            // Domingo 20/Sep - Diurno (Carolina - Sin Historial / none)
            [
                'booking_code' => 'TS-RES-2026-SEP08',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $carolina?->id,
                'date' => '2026-09-20',
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'modality' => 'diurno',
                'check_in_time' => '10:00',
                'check_out_time' => '20:00',
                'guests_count' => 28,
                'guests_adults' => 20,
                'guests_children' => 8,
                'event_type' => 'Reunión familiar primavera',
                'insurance_code' => 'POL-TRIUNFO-9482',
                'status' => 'completed',
                'cbu_verified' => true,
                'owner_rating' => 'none',
                'owner_observation' => null,
                'notes' => 'Excelente experiencia sin quejas.',
            ],
            // Sábado 26/Sep - Pernocte
            [
                'booking_code' => 'TS-RES-2026-SEP09',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $lorena?->id,
                'date' => '2026-09-26',
                'shift' => 'Pernocte Cabaña / Quincho (12:00 a 10:00 hs)',
                'modality' => 'pernocte',
                'check_in_time' => '12:00',
                'check_out_time' => '10:00',
                'guests_count' => 20,
                'guests_adults' => 16,
                'guests_children' => 4,
                'event_type' => 'Aniversario de Bodas con Pernocte',
                'insurance_code' => 'POL-TRIUNFO-9482',
                'status' => 'completed',
                'cbu_verified' => true,
                'notes' => 'Alquiler liquidado y verificado en tranquera.',
            ],
            // Domingo 27/Sep - Diurno
            [
                'booking_code' => 'TS-RES-2026-SEP10',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $damian?->id,
                'date' => '2026-09-27',
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'modality' => 'diurno',
                'check_in_time' => '10:00',
                'check_out_time' => '20:00',
                'guests_count' => 30,
                'guests_adults' => 26,
                'guests_children' => 4,
                'event_type' => 'Despedida de Soltero Diurna',
                'insurance_code' => 'POL-TRIUNFO-9482',
                'status' => 'completed',
                'cbu_verified' => true,
                'notes' => 'Límite sonoro respetado (65 dB norma vecinal).',
            ],

            // --- CABAÑA & POSADA EL REFUGIO (ID 10, ZONDA) ---
            // Sábado 05/Sep
            [
                'booking_code' => 'TS-RES-2026-REF01',
                'property_id' => $propertyRefugio?->id ?? $propertyOlivos?->id,
                'user_id' => $carolina?->id,
                'date' => '2026-09-05',
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'guests_count' => 22,
                'guests_adults' => 16,
                'guests_children' => 6,
                'event_type' => 'Cumpleaños 15 Diurno',
                'insurance_code' => 'POL-TRIUNFO-9104',
                'status' => 'completed',
                'cbu_verified' => true,
                'notes' => 'Completado en Zonda. Clientes maravillados con la vista.',
            ],
            // Domingo 06/Sep
            [
                'booking_code' => 'TS-RES-2026-REF02',
                'property_id' => $propertyRefugio?->id ?? $propertyOlivos?->id,
                'user_id' => $carlos?->id,
                'date' => '2026-09-06',
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'guests_count' => 20,
                'guests_adults' => 18,
                'guests_children' => 2,
                'event_type' => 'Día familiar en la montaña',
                'insurance_code' => 'POL-TRIUNFO-9104',
                'status' => 'completed',
                'cbu_verified' => true,
                'notes' => 'Todo conforme a normas de convivencia.',
            ],
            // Jueves 17/Sep (Día de semana)
            [
                'booking_code' => 'TS-RES-2026-REF03',
                'property_id' => $propertyRefugio?->id ?? $propertyOlivos?->id,
                'user_id' => $lucia?->id,
                'date' => '2026-09-17',
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'guests_count' => 12,
                'guests_adults' => 10,
                'guests_children' => 2,
                'event_type' => 'Retiro corporativo / Almuerzo',
                'insurance_code' => 'POL-TRIUNFO-9104',
                'status' => 'completed',
                'cbu_verified' => true,
                'notes' => 'Uso de galería y piscina climatizada.',
            ],
            // Sábado 19/Sep
            [
                'booking_code' => 'TS-RES-2026-REF04',
                'property_id' => $propertyRefugio?->id ?? $propertyOlivos?->id,
                'user_id' => $ignacio?->id,
                'date' => '2026-09-19',
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'guests_count' => 24,
                'guests_adults' => 20,
                'guests_children' => 4,
                'event_type' => 'Asado campestre fin de semana',
                'insurance_code' => 'POL-TRIUNFO-9104',
                'status' => 'completed',
                'cbu_verified' => true,
                'notes' => 'Póliza cubierta #POL-TRIUNFO-9104.',
            ],
            // Domingo 20/Sep
            [
                'booking_code' => 'TS-RES-2026-REF05',
                'property_id' => $propertyRefugio?->id ?? $propertyOlivos?->id,
                'user_id' => $beatriz?->id,
                'date' => '2026-09-20',
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'guests_count' => 18,
                'guests_adults' => 14,
                'guests_children' => 4,
                'event_type' => 'Festejo de Bienvenida Primavera',
                'insurance_code' => 'POL-TRIUNFO-9104',
                'status' => 'completed',
                'cbu_verified' => true,
                'notes' => 'Completado sin reclamos.',
            ],
            // Sábado 26/Sep
            [
                'booking_code' => 'TS-RES-2026-REF06',
                'property_id' => $propertyRefugio?->id ?? $propertyOlivos?->id,
                'user_id' => $carolina?->id,
                'date' => '2026-09-26',
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'guests_count' => 25,
                'guests_adults' => 20,
                'guests_children' => 5,
                'event_type' => 'Cumpleaños familiar',
                'insurance_code' => 'POL-TRIUNFO-9104',
                'status' => 'completed',
                'cbu_verified' => true,
                'notes' => 'Aforo homologado 25 personas al 100%.',
            ],
        ];

        // -------------------------------------------------------------
        // 2. PRÓXIMAS RESERVAS: OCTUBRE 2026 / FECHAS ACTIVAS
        // -------------------------------------------------------------
        $upcomingBookings = [
            [
                'booking_code' => 'TS-RES-2026-0849',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $esteban?->id,
                'date' => Carbon::today()->addDays(4)->toDateString(),
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'modality' => 'diurno',
                'check_in_time' => '10:00',
                'check_out_time' => '20:00',
                'guests_count' => 28,
                'guests_adults' => 24,
                'guests_children' => 4,
                'event_type' => 'Cumpleaños 40 de Esteban',
                'insurance_code' => 'TS-SEG-4821',
                'status' => 'confirmed',
                'cbu_verified' => true,
                'notes' => 'Aforo 56% homologado. Incluye póliza Triunfo Seguros.',
            ],
            [
                'booking_code' => 'TS-RES-2026-0850',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $lorena?->id,
                'date' => Carbon::today()->addDays(5)->toDateString(),
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'modality' => 'diurno',
                'check_in_time' => '10:00',
                'check_out_time' => '20:00',
                'guests_count' => 38,
                'guests_adults' => 28,
                'guests_children' => 10,
                'event_type' => 'Bautismo familiar',
                'insurance_code' => 'POL-TRIUNFO-9482',
                'status' => 'confirmed',
                'cbu_verified' => true,
                'notes' => 'Excelente calificación previa.',
            ],
            [
                'booking_code' => 'TS-RES-2026-0851',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $damian?->id,
                'date' => Carbon::today()->addDays(11)->toDateString(),
                'shift' => 'Fiesta Nocturna (21:00 a 05:00 hs)',
                'modality' => 'fiesta_nocturna',
                'check_in_time' => '21:00',
                'check_out_time' => '05:00',
                'guests_count' => 35,
                'guests_adults' => 30,
                'guests_children' => 5,
                'event_type' => 'Asado & Peña Nocturna de amigos',
                'insurance_code' => 'POL-TRIUNFO-9482',
                'status' => 'confirmed',
                'cbu_verified' => true,
                'notes' => 'Uso de parrillero techado y cerco de pileta revisado.',
            ],
            [
                'booking_code' => 'TS-RES-2026-0852',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $ignacio?->id,
                'date' => Carbon::today()->addDays(12)->toDateString(),
                'shift' => 'Evento Especial (12:00 a 02:00 hs)',
                'modality' => 'evento_especial',
                'check_in_time' => '12:00',
                'check_out_time' => '02:00',
                'guests_count' => 45,
                'guests_adults' => 40,
                'guests_children' => 5,
                'event_type' => 'Cumpleaños 50 años',
                'insurance_code' => 'TS-SEG-4821',
                'status' => 'confirmed',
                'cbu_verified' => true,
                'notes' => 'Cancelación 100% abonada con póliza extendida.',
            ],
            [
                'booking_code' => 'TS-RES-2026-0853',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $beatriz?->id,
                'date' => Carbon::today()->addDays(13)->toDateString(),
                'shift' => 'Pernocte Cabaña / Quincho (12:00 a 10:00 hs)',
                'modality' => 'pernocte',
                'check_in_time' => '12:00',
                'check_out_time' => '10:00',
                'guests_count' => 24,
                'guests_adults' => 16,
                'guests_children' => 8,
                'event_type' => 'Finde Familiar con Pernocte',
                'insurance_code' => 'POL-TRIUNFO-9482',
                'status' => 'confirmed',
                'cbu_verified' => true,
                'notes' => 'Constatado seguro y salva-vidas perimetral.',
            ],
            [
                'booking_code' => 'TS-RES-2026-0854',
                'property_id' => $propertyOlivos?->id,
                'user_id' => $carolina?->id,
                'date' => Carbon::today()->addDays(18)->toDateString(),
                'shift' => 'Turno Diurno (10:00 a 20:00 hs)',
                'modality' => 'diurno',
                'check_in_time' => '10:00',
                'check_out_time' => '20:00',
                'guests_count' => 24,
                'guests_adults' => 18,
                'guests_children' => 6,
                'event_type' => 'Cumpleaños 15 Diurno',
                'insurance_code' => 'POL-TRIUNFO-9104',
                'status' => 'confirmed',
                'cbu_verified' => true,
                'notes' => 'Inquilina Sanjuanina DNI 36.541.902 con perfil validado.',
            ],
        ];

        $allBookings = array_merge($septemberBookings, $upcomingBookings);

        foreach ($allBookings as $bData) {
            if (! $bData['property_id'] || ! $bData['user_id']) {
                continue;
            }

            Booking::updateOrCreate(
                ['booking_code' => $bData['booking_code']],
                $bData
            );

            // Mantener sincronizado el calendario de disponibilidad de la propiedad
            PropertyAvailability::where('property_id', $bData['property_id'])
                ->whereDate('date', $bData['date'])
                ->delete();

            PropertyAvailability::create([
                'property_id' => $bData['property_id'],
                'date' => Carbon::parse($bData['date'])->format('Y-m-d'),
                'status' => 'booked',
                'notes' => $bData['event_type'] . ' - ' . $bData['booking_code'],
            ]);
        }

        // -------------------------------------------------------------
        // 3. MANTENIMIENTOS Y BLOQUEOS DE SEPTIEMBRE Y OCTUBRE
        // -------------------------------------------------------------
        if ($propertyOlivos) {
            $extraAvailabilities = [
                // Septiembre 2026
                ['date' => '2026-09-08', 'status' => 'maintenance', 'notes' => 'Mantenimiento: Filtrado & Poda'],
                ['date' => '2026-09-15', 'status' => 'maintenance', 'notes' => 'Mantenimiento: Pintura y Cloro'],
                ['date' => '2026-09-22', 'status' => 'blocked', 'notes' => 'Uso Familiar: Cumpleaños Nieto'],
                // Octubre 2026
                ['date' => '2026-10-06', 'status' => 'maintenance', 'notes' => 'Mantenimiento: Filtrado & Césped'],
                ['date' => '2026-10-09', 'status' => 'blocked', 'notes' => 'Uso Familiar'],
                ['date' => '2026-10-18', 'status' => 'blocked', 'notes' => 'Bloqueo Clima: Viento Zonda Fuerte'],
            ];

            foreach ($extraAvailabilities as $extra) {
                PropertyAvailability::where('property_id', $propertyOlivos->id)
                    ->whereDate('date', $extra['date'])
                    ->delete();

                PropertyAvailability::create([
                    'property_id' => $propertyOlivos->id,
                    'date' => $extra['date'],
                    'status' => $extra['status'],
                    'notes' => $extra['notes'],
                ]);
            }
        }

        if ($propertyRefugio) {
            $extraRefugio = [
                ['date' => '2026-09-09', 'status' => 'maintenance', 'notes' => 'Mantenimiento: Caldera & Pileta'],
                ['date' => '2026-09-23', 'status' => 'blocked', 'notes' => 'Uso Familiar: Fin de semana Elena'],
            ];

            foreach ($extraRefugio as $extra) {
                PropertyAvailability::where('property_id', $propertyRefugio->id)
                    ->whereDate('date', $extra['date'])
                    ->delete();

                PropertyAvailability::create([
                    'property_id' => $propertyRefugio->id,
                    'date' => $extra['date'],
                    'status' => $extra['status'],
                    'notes' => $extra['notes'],
                ]);
            }
        }
    }
}
