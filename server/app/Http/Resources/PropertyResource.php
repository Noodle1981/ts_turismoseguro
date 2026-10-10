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
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'images' => PropertyImageResource::collection($this->whenLoaded('images')),
            'amenities' => $this->whenLoaded('amenities', fn () => $this->amenities->pluck('amenity')->values()->all()),
            'rules' => $this->whenLoaded('rules', fn () => $this->rules->pluck('rule')->values()->all()),
        ];
    }
}
