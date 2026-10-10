<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValidatorCommission extends Model
{
    use HasFactory;

    protected $fillable = [
        'validator_id',
        'property_id',
        'period_month',
        'period_year',
        'is_first_month',
        'amount',
        'rate_percentage',
        'status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'period_month' => 'integer',
            'period_year' => 'integer',
            'is_first_month' => 'boolean',
            'amount' => 'integer',
            'rate_percentage' => 'float',
            'paid_at' => 'datetime',
        ];
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validator_id');
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
