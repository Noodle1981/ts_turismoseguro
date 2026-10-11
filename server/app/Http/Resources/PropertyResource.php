<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'property_type' => $this->property_type,
            'location' => $this->location,
            'address' => $this->address,
            'description' => $this->description,
            'price' => (int) $this->price,
            'price_type' => $this->price_type,
            'capacity' => (int) $this->capacity,
            'bedrooms' => (int) $this->bedrooms,
            'bathrooms' => (int) $this->bathrooms,
            'host_whatsapp' => $this->host_whatsapp,
            'host_phone' => $this->host_phone,
            'verification_status' => $this->verification_status,
            'publication_status' => $this->publication_status,
            'verified_at' => $this->verified_at?->toIso8601String(),
            'verification_certificate_number' => $this->verification_certificate_number,
            'verified_facade_image' => $this->verified_facade_image ? asset($this->verified_facade_image) : null,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'featured' => (bool) $this->featured,

            // Tarifas multimodales
            'tariffs' => [
                'lunes_jueves' => (int) ($this->tariff_lunes_jueves ?? $this->price),
                'viernes_domingo' => (int) ($this->tariff_viernes_domingo ?? $this->price),
                'sabado_feriados' => (int) ($this->tariff_sabado_feriados ?? $this->price),
                'evento_especial' => (int) ($this->tariff_evento_especial ?? 90000),
                'pernocte_camas' => $this->tariff_pernocte_camas ? (int) $this->tariff_pernocte_camas : null,
                'pernocte_carpas' => $this->tariff_pernocte_carpas ? (int) $this->tariff_pernocte_carpas : null,
                'finde_completo' => $this->tariff_finde_completo ? (int) $this->tariff_finde_completo : null,
                'fiesta_nocturna' => $this->tariff_fiesta_nocturna ? (int) $this->tariff_fiesta_nocturna : null,
            ],

            // Franja diurna y días activos
            'schedule' => [
                'start' => $this->schedule_start ?? '10:00',
                'end' => $this->schedule_end ?? '20:00',
                'active_days' => $this->active_days ?? [0, 4, 5, 6],
            ],

            // Visibilidad y vacaciones
            'is_listing_visible' => (bool) ($this->is_listing_visible ?? true),
            'pause_mode' => $this->pause_mode,
            'pause_start_date' => $this->pause_start_date?->format('Y-m-d'),
            'pause_end_date' => $this->pause_end_date?->format('Y-m-d'),

            // Póliza y Seguro
            'insurance' => [
                'company' => $this->insurance_company ?? 'Triunfo Seguros',
                'policy_number' => $this->policy_number,
                'valid_until' => $this->policy_valid_until?->format('Y-m-d'),
                'acta_fecha' => $this->acta_inspeccion_fecha,
            ],

            'department' => new DepartmentResource($this->whenLoaded('department')),
            'locality' => new LocalityResource($this->whenLoaded('locality')),
            'images' => PropertyImageResource::collection($this->whenLoaded('images')),
            'amenities' => $this->whenLoaded('amenities', fn () => $this->amenities->pluck('amenity')->values()->all()),
            'rules' => $this->whenLoaded('rules', fn () => $this->rules->pluck('rule')->values()->all()),

            // Última solicitud de verificación in situ (si está cargada)
            'verification_request' => $this->whenLoaded('verificationRequests', function () {
                $latest = $this->verificationRequests->sortByDesc('created_at')->first();
                if (! $latest) {
                    return null;
                }
                return [
                    'id' => $latest->id,
                    'status' => $latest->status,
                    'security_token' => $latest->security_token,
                    'notes' => $latest->notes,
                    'created_at' => $latest->created_at?->toIso8601String(),
                    'validator' => $latest->validator ? [
                        'id' => $latest->validator->id,
                        'name' => $latest->validator->name,
                        'phone' => $latest->validator->phone,
                        'dni' => $latest->validator->dni,
                        'origin_city' => $latest->validator->origin_city,
                    ] : null,
                ];
            }),
        ];
    }
}
