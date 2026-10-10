<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyAvailability;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use InvalidArgumentException;

class AvailabilityService
{
    /**
     * Update dates range in property availability calendar.
     */
    public function updateRange(Property $property, string $startDate, string $endDate, string $action): void
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->startOfDay();

        if ($end->lessThan($start)) {
            throw new InvalidArgumentException('La fecha de fin no puede ser anterior a la fecha de inicio.');
        }

        if ($start->lessThan(Carbon::today())) {
            throw new InvalidArgumentException('No es posible modificar fechas del pasado.');
        }

        $period = CarbonPeriod::create($start, $end);

        if ($action === 'block') {
            PropertyAvailability::where('property_id', $property->id)
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->delete();

            foreach ($period as $date) {
                PropertyAvailability::create([
                    'property_id' => $property->id,
                    'date' => $date->format('Y-m-d'),
                    'status' => 'blocked',
                    'notes' => 'Bloqueado por el anfitrión',
                ]);
            }
        } elseif ($action === 'free') {
            PropertyAvailability::where('property_id', $property->id)
                ->whereBetween('date', [$start->toDateString(), $end->toDateString()])
                ->delete();
        }
    }
}
