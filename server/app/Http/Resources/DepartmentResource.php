<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepartmentResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'hero_image' => $this->hero_image ? asset($this->hero_image) : null,
            'is_active' => (bool) $this->is_active,
            'properties_count' => $this->whenCounted('properties'),
            'experiences_count' => $this->whenCounted('experiences'),
        ];
    }
}
