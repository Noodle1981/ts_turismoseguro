<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienceResource extends JsonResource
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
            'category' => $this->category,
            'main_location' => $this->main_location,
            'price_per_person' => (int) $this->price_per_person,
            'duration' => $this->duration,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'operator_name' => $this->operator_name,
            'operator_whatsapp' => $this->operator_whatsapp,
            'operator_email' => $this->operator_email,
            'is_verified' => (bool) $this->is_verified,
            'featured' => (bool) $this->featured,
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'images' => $this->whenLoaded('images', fn () => $this->images->map(fn ($img) => [
                'id' => $img->id,
                'image_path' => str_starts_with($img->image_path, 'http') ? $img->image_path : asset($img->image_path),
                'caption' => $img->caption,
                'is_primary' => (bool) $img->is_primary,
            ])),
            'includes' => $this->whenLoaded('includes', fn () => $this->includes->map(fn ($inc) => [
                'id' => $inc->id,
                'item' => $inc->item,
                'is_included' => (bool) $inc->is_included,
            ])),
            'recommendations' => $this->whenLoaded('recommendations', fn () => $this->recommendations->pluck('recommendation')->values()->all()),
        ];
    }
}
