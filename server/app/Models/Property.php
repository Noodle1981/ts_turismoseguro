<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'department_id',
        'validator_id',
        'title',
        'slug',
        'property_type',
        'location',
        'address',
        'description',
        'price',
        'price_type',
        'capacity',
        'bedrooms',
        'bathrooms',
        'host_whatsapp',
        'host_phone',
        'verification_status',
        'publication_status',
        'verified_at',
        'verification_certificate_number',
        'verified_facade_image',
        'latitude',
        'longitude',
        'featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'capacity' => 'integer',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
            'featured' => 'boolean',
            'verified_at' => 'datetime',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    /**
     * Scope query to only published and verified properties.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('publication_status', 'published')
            ->where('verification_status', 'verified');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validator_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class)->orderBy('sort_order');
    }

    public function amenities(): HasMany
    {
        return $this->hasMany(PropertyAmenity::class);
    }

    public function rules(): HasMany
    {
        return $this->hasMany(PropertyRule::class);
    }

    public function availabilities(): HasMany
    {
        return $this->hasMany(PropertyAvailability::class);
    }

    public function verificationRequests(): HasMany
    {
        return $this->hasMany(VerificationRequest::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
