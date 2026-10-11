<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PropertyResource;
use App\Models\Booking;
use App\Models\Property;
use App\Models\PropertyAmenity;
use App\Models\PropertyAvailability;
use App\Models\PropertyRule;
use App\Models\VerificationRequest;
use App\Services\AvailabilityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;

class ClientPropertyController extends Controller
{
    public function __construct(
        protected AvailabilityService $availabilityService
    ) {}

    /**
     * List properties owned by the authenticated client.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $properties = Property::where('user_id', $request->user()->id)
            ->with(['department', 'images', 'amenities', 'rules'])
            ->orderBy('created_at', 'desc')
            ->get();

        return PropertyResource::collection($properties);
    }

    /**
     * Store a new property owned by the authenticated client.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id',
            'property_type' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'description' => 'required|string',
            'price' => 'required|integer|min:1',
            'price_type' => 'required|string|in:por día,/noche',
            'capacity' => 'required|integer|min:1',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:1',
            'host_whatsapp' => 'nullable|string|max:50',
            'host_phone' => 'nullable|string|max:50',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:50',
            'rules' => 'nullable|array',
            'rules.*' => 'string|max:255',
        ]);

        $slugBase = Str::slug($validated['title']);
        $slug = $slugBase.'-'.Str::random(5);

        $property = Property::create([
            'user_id' => $request->user()->id,
            'department_id' => $validated['department_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'property_type' => $validated['property_type'],
            'location' => $validated['location'],
            'address' => $validated['address'] ?? null,
            'description' => $validated['description'],
            'price' => $validated['price'],
            'price_type' => $validated['price_type'],
            'capacity' => $validated['capacity'],
            'bedrooms' => $validated['bedrooms'] ?? 0,
            'bathrooms' => $validated['bathrooms'] ?? 1,
            'host_whatsapp' => $validated['host_whatsapp'] ?? $request->user()->phone,
            'host_phone' => $validated['host_phone'] ?? $request->user()->phone,
            'verification_status' => 'pending',
            'publication_status' => 'draft', // Regla estricta: nunca autopublicado
        ]);

        if (! empty($validated['amenities'])) {
            foreach ($validated['amenities'] as $amenity) {
                PropertyAmenity::create([
                    'property_id' => $property->id,
                    'amenity' => $amenity,
                ]);
            }
        }

        if (! empty($validated['rules'])) {
            foreach ($validated['rules'] as $rule) {
                PropertyRule::create([
                    'property_id' => $property->id,
                    'rule' => $rule,
                ]);
            }
        }

        $property->load(['department', 'images', 'amenities', 'rules']);

        return response()->json([
            'message' => 'Propiedad creada con éxito en borrador.',
            'data' => new PropertyResource($property),
        ], 201);
    }

    /**
     * Show a client property by ID.
     */
    public function show(int $id, Request $request): JsonResponse
    {
        $property = Property::where('user_id', $request->user()->id)
            ->with(['department', 'images', 'amenities', 'rules', 'availabilities'])
            ->findOrFail($id);

        return response()->json([
            'data' => new PropertyResource($property),
        ]);
    }

    /**
     * Update an existing property owned by client.
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $property = Property::where('user_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'department_id' => 'sometimes|required|exists:departments,id',
            'locality_id' => 'nullable|exists:localities,id',
            'property_type' => 'sometimes|required|string|max:100',
            'location' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|integer|min:1',
            'price_type' => 'sometimes|required|string|in:por día,/noche',
            'capacity' => 'sometimes|required|integer|min:1',
            'bedrooms' => 'nullable|integer|min:0',
            'bathrooms' => 'nullable|integer|min:1',
            'host_whatsapp' => 'nullable|string|max:50',
            'host_phone' => 'nullable|string|max:50',

            // Tarifas multimodales
            'tariff_lunes_jueves' => 'nullable|integer|min:0',
            'tariff_viernes_domingo' => 'nullable|integer|min:0',
            'tariff_sabado_feriados' => 'nullable|integer|min:0',
            'tariff_evento_especial' => 'nullable|integer|min:0',
            'tariff_pernocte_camas' => 'nullable|integer|min:0',
            'tariff_pernocte_carpas' => 'nullable|integer|min:0',
            'tariff_finde_completo' => 'nullable|integer|min:0',
            'tariff_fiesta_nocturna' => 'nullable|integer|min:0',

            // Horarios y Días
            'schedule_start' => 'nullable|string|max:10',
            'schedule_end' => 'nullable|string|max:10',
            'active_days' => 'nullable|array',
            'active_days.*' => 'integer|between:0,6',

            // Visibilidad y Vacaciones
            'is_listing_visible' => 'nullable|boolean',
            'pause_mode' => 'nullable|string|in:indefinite,vacation',
            'pause_start_date' => 'nullable|date',
            'pause_end_date' => 'nullable|date|after_or_equal:pause_start_date',

            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:50',
            'rules' => 'nullable|array',
            'rules.*' => 'string|max:255',
        ]);

        $property->update($validated);

        if (isset($validated['amenities'])) {
            PropertyAmenity::where('property_id', $property->id)->delete();
            foreach ($validated['amenities'] as $amenity) {
                PropertyAmenity::create([
                    'property_id' => $property->id,
                    'amenity' => $amenity,
                ]);
            }
        }

        if (isset($validated['rules'])) {
            PropertyRule::where('property_id', $property->id)->delete();
            foreach ($validated['rules'] as $rule) {
                PropertyRule::create([
                    'property_id' => $property->id,
                    'rule' => $rule,
                ]);
            }
        }

        $property->load(['department', 'images', 'amenities', 'rules']);

        return response()->json([
            'message' => 'Propiedad actualizada.',
            'data' => new PropertyResource($property),
        ]);
    }

    /**
     * Get bookings and availability dates for client property.
     */
    public function availability(int $id, Request $request): JsonResponse
    {
        $property = Property::where('user_id', $request->user()->id)->findOrFail($id);

        $availabilities = PropertyAvailability::where('property_id', $property->id)
            ->orderBy('date')
            ->get(['id', 'date', 'status', 'notes']);

        $bookings = $property->bookings()
            ->with('user:id,name,email,phone,dni')
            ->orderBy('date')
            ->get();

        return response()->json([
            'availabilities' => $availabilities,
            'bookings' => $bookings,
        ]);
    }

    /**
     * Toggle or set status for a specific date on a property.
     */
    public function toggleAvailabilityDate(int $id, Request $request): JsonResponse
    {
        $property = Property::where('user_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'date' => 'required|date',
            'status' => 'required|string|in:available,blocked,maintenance',
            'notes' => 'nullable|string|max:150',
        ]);

        if ($validated['status'] === 'available') {
            PropertyAvailability::where('property_id', $property->id)
                ->where('date', $validated['date'])
                ->delete();

            return response()->json([
                'message' => 'Día liberado para alquiler.',
                'date' => $validated['date'],
                'status' => 'available',
            ]);
        }

        $record = PropertyAvailability::updateOrCreate(
            [
                'property_id' => $property->id,
                'date' => $validated['date'],
            ],
            [
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? ($validated['status'] === 'maintenance' ? 'Mantenimiento' : 'Bloqueado por anfitrión'),
            ]
        );

        return response()->json([
            'message' => 'Disponibilidad actualizada en base de datos.',
            'data' => $record,
        ]);
    }

    /**
     * Update range availability (block/free).
     */
    public function updateAvailability(int $id, Request $request): JsonResponse
    {
        $property = Property::where('user_id', $request->user()->id)->findOrFail($id);

        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'action' => 'required|string|in:block,free',
        ]);

        try {
            $this->availabilityService->updateRange(
                $property,
                $validated['start_date'],
                $validated['end_date'],
                $validated['action']
            );

            return response()->json([
                'message' => 'Calendario actualizado correctamente.',
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Request in-situ physical verification for a property.
     */
    public function requestVerification(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $property = Property::where('user_id', $request->user()->id)
            ->findOrFail($validated['property_id']);

        if ($property->verification_status === 'verified') {
            return response()->json([
                'message' => 'Esta propiedad ya cuenta con verificación aprobada.',
            ], 422);
        }

        $verificationRequest = VerificationRequest::create([
            'property_id' => $property->id,
            'client_id' => $request->user()->id,
            'status' => 'pending',
            'notes' => $validated['notes'] ?? 'Solicitud iniciada por el anfitrión desde el panel.',
        ]);

        $property->update([
            'verification_status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Solicitud de verificación física in situ enviada con éxito.',
            'request_id' => $verificationRequest->id,
            'verification_status' => $property->verification_status,
        ]);
    }

    /**
     * Get bookings and tenants list (proximos and pasados) for client property.
     */
    public function tenants(int $id, Request $request): JsonResponse
    {
        $property = Property::where('user_id', $request->user()->id)->findOrFail($id);

        $bookings = $property->bookings()
            ->with('user:id,name,email,phone,dni')
            ->orderBy('date', 'desc')
            ->get();

        $today = now()->toDateString();

        $mapBooking = function ($b) {
            $name = $b->user?->name ?? 'Inquilino';
            $parts = array_values(array_filter(explode(' ', trim($name))));
            $initials = count($parts) >= 2
                ? mb_strtoupper(mb_substr($parts[0], 0, 1) . mb_substr($parts[1], 0, 1))
                : mb_strtoupper(mb_substr($name, 0, 2));

            return [
                'id' => (string) $b->id,
                'booking_code' => $b->booking_code,
                'name' => $name,
                'initials' => $initials,
                'dni' => $b->user?->dni ?? 'S/DNI',
                'phone' => $b->user?->phone ?? 'S/Tel',
                'date' => $b->date ? $b->date->format('Y-m-d') : null,
                'date_formatted' => $b->date ? $b->date->format('d/m/Y') : null,
                'time' => $b->shift ?? '10:00 a 20:00 hs',
                'guestsText' => "{$b->guests_count} personas ({$b->guests_adults} adultos, {$b->guests_children} niños)",
                'guestsAdults' => (int) $b->guests_adults,
                'guestsChildren' => (int) $b->guests_children,
                'eventType' => $b->event_type ?? 'Alquiler diurno',
                'insuranceBadge' => $b->insurance_code ?? 'Póliza TS-SEG-4821',
                'history' => $b->notes ?? 'Inquilino verificado en Red Turismo Seguro',
                'status' => $b->status,
                'ownerRating' => $b->owner_rating ?? 'none',
            ];
        };

        $proximos = $bookings->filter(fn ($b) => $b->date && $b->date->toDateString() >= $today && $b->status !== 'completed')
            ->values()
            ->map($mapBooking);

        $pasados = $bookings->filter(fn ($b) => !$b->date || $b->date->toDateString() < $today || $b->status === 'completed')
            ->values()
            ->map($mapBooking);

        return response()->json([
            'proximos' => $proximos,
            'pasados' => $pasados,
        ]);
    }

    /**
     * Rate a tenant booking (thumbs up, down, or reset).
     */
    public function rateBooking(int $bookingId, Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rating' => 'required|string|in:up,down,none',
        ]);

        $booking = Booking::whereHas('property', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->findOrFail($bookingId);

        $booking->update([
            'owner_rating' => $validated['rating'],
        ]);

        return response()->json([
            'message' => 'Calificación del inquilino actualizada correctamente.',
            'booking_id' => $booking->id,
            'owner_rating' => $booking->owner_rating,
        ]);
    }
}
