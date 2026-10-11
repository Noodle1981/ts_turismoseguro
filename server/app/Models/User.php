<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'dni',
        'origin_country',
        'origin_province',
        'origin_city',
        'is_local_resident',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_local_resident' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'user_id');
    }

    public function assignedAudits(): HasMany
    {
        return $this->hasMany(VerificationRequest::class, 'validator_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(ValidatorCommission::class, 'validator_id');
    }
}
