<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Experience extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'department_id',
        'title',
        'slug',
        'category',
        'main_location',
        'price_per_person',
        'duration',
        'short_description',
        'long_description',
        'operator_name',
        'operator_whatsapp',
        'operator_email',
        'is_verified',
        'featured',
    ];

    protected function casts(): array
    {
        return [
            'price_per_person' => 'integer',
            'is_verified' => 'boolean',
            'featured' => 'boolean',
        ];
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ExperienceImage::class)->orderBy('sort_order');
    }

    public function includes(): HasMany
    {
        return $this->hasMany(ExperienceInclude::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(ExperienceRecommendation::class);
    }
}
