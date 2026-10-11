<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'client_id',
        'validator_id',
        'supervisor_id',
        'status',
        'security_token',
        'rejection_reason',
        'visit_date',
        'checklist',
        'notes',
        'gps_latitude',
        'gps_longitude',
    ];

    protected function casts(): array
    {
        return [
            'visit_date' => 'date',
            'checklist' => 'array',
            'gps_latitude' => 'float',
            'gps_longitude' => 'float',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validator_id');
    }

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }
}
