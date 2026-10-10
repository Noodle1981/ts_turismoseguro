<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin User
 */
class UserResource extends JsonResource
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
            'email' => $this->email,
            'role' => $this->role,
            'phone' => $this->phone,
            'origin_country' => $this->origin_country,
            'origin_province' => $this->origin_province,
            'origin_city' => $this->origin_city,
            'is_local_resident' => (bool) $this->is_local_resident,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
