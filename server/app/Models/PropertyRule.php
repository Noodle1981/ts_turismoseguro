<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'rule',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
}
