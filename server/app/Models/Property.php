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
        'locality_id',
        'validator_id',
        'title',
        'slug',
        'property_type',
        'location',
        'address',
        'description',
        'price',
        'price_type',
        'tariff_lunes_jueves',
        'tariff_viernes_domingo',
        'tariff_sabado_feriados',
        'tariff_evento_especial',
        'tariff_pernocte_camas',
        'tariff_pernocte_carpas',
        'tariff_finde_completo',
        'tariff_fiesta_nocturna',
        'schedule_start',
        'schedule_end',
        'active_days',
        'is_listing_visible',
        'pause_mode',
        'pause_start_date',
        'pause_end_date',
        'insurance_company',
        'policy_number',
        'policy_valid_until',
        'acta_inspeccion_fecha',
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
            'tariff_lunes_jueves' => 'integer',
            'tariff_viernes_domingo' => 'integer',
            'tariff_sabado_feriados' => 'integer',
            'tariff_evento_especial' => 'integer',
            'tariff_pernocte_camas' => 'integer',
            'tariff_pernocte_carpas' => 'integer',
            'tariff_finde_completo' => 'integer',
            'tariff_fiesta_nocturna' => 'integer',
            'active_days' => 'array',
            'is_listing_visible' => 'boolean',
            'pause_start_date' => 'date',
            'pause_end_date' => 'date',
            'policy_valid_until' => 'date',
            'capacity' => 'integer',
            'bedrooms' => 'integer',
            'bathrooms' => 'integer',
            'featured' => 'boolean',
            'verified_at' => 'datetime',
            'latitude' => 'float',
            'longitude' => 'float',
        ];
    }

    public function locality(): BelongsTo
    {
        return $this->belongsTo(Locality::class);
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
