<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'category',
        'start_date',
        'end_date',
        'venue',
        'impact_departments',
        'suggested_rate_multiplier',
        'allows_overnight',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'impact_departments' => 'array',
        'suggested_rate_multiplier' => 'float',
        'allows_overnight' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Scope para filtrar eventos que impacten en un departamento dado o a toda la provincia.
     */
    public function scopeForDepartment($query, ?string $department)
    {
        if (!$department) {
            return $query;
        }

        return $query->where(function ($q) use ($department) {
            $q->whereJsonContains('impact_departments', 'TODOS')
              ->orWhereJsonContains('impact_departments', $department);
        });
    }
}
