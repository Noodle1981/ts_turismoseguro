<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'property_id',
        'user_id',
        'date',
        'shift',
        'guests_count',
        'guests_adults',
        'guests_children',
        'event_type',
        'insurance_code',
        'status',
        'total_price',
        'deposit_paid',
        'balance_due',
        'cbu_verified',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'guests_count' => 'integer',
            'guests_adults' => 'integer',
            'guests_children' => 'integer',
            'total_price' => 'decimal:2',
            'deposit_paid' => 'decimal:2',
            'balance_due' => 'decimal:2',
            'cbu_verified' => 'boolean',
        ];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
