<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepartmentResource;
use App\Http\Resources\PropertyResource;
use App\Models\Department;
use App\Models\Property;
use App\Models\PropertyAvailability;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CatalogController extends Controller
{
    /**
     * List all departments with count of active properties.
     * Si se solicita with_offers=true o for_filter=true, solo retorna departamentos con ofertas activas.
     */
    public function departments(Request $request): AnonymousResourceCollection
    {
        $query = Department::where('is_active', true)
            ->withCount(['properties' => function ($q) {
                $q->published();
            }])
            ->orderBy('name');

        if ($request->boolean('with_offers') || $request->boolean('for_filter')) {
            $query->has('properties');
        }

        $departments = $query->get();

        return DepartmentResource::collection($departments);
    }

    /**
     * List verified and published properties with search filters and pagination.
     */
    public function properties(Request $request): AnonymousResourceCollection
    {
        $query = Property::published()
            ->with(['department', 'images', 'amenities']);

        // Filtro por departamento (slug o nombre)
        if ($department = $request->query('department')) {
            $query->whereHas('department', function ($q) use ($department) {
                $q->where('slug', $department)
                    ->orWhere('name', 'like', "%{$department}%");
            });
        }

        // Filtro por tipo de propiedad
        if ($propertyType = $request->query('property_type')) {
            $query->where('property_type', $propertyType);
        }

        // Filtro por capacidad mínima
        if ($capacity = $request->query('capacity')) {
            $query->where('capacity', '>=', (int) $capacity);
        }

        // Filtro por precio máximo
        if ($maxPrice = $request->query('max_price')) {
            $query->where('price', '<=', (int) $maxPrice);
        }

        // Búsqueda por texto en título, ubicación o descripción
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filtro por comodidades (separadas por coma)
        if ($amenities = $request->query('amenities')) {
            $amenityList = array_map('trim', explode(',', $amenities));
            foreach ($amenityList as $amenity) {
                if (! empty($amenity)) {
                    $query->whereHas('amenities', function ($q) use ($amenity) {
                        $q->where('name', 'like', "%{$amenity}%");
                    });
                }
            }
        }

        // Ordenamiento
        match ($request->query('sort')) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'recent' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('featured', 'desc')->orderBy('created_at', 'desc'),
        };

        $properties = $query->paginate(9);

        return PropertyResource::collection($properties);
    }

    /**
     * Get property details by slug.
     */
    public function propertyDetail(string $slug): JsonResponse
    {
        $property = Property::published()
            ->with(['department', 'images', 'amenities', 'rules', 'availabilities'])
            ->where('slug', $slug)
            ->first();

        if (! $property) {
            return response()->json([
                'message' => 'Propiedad no encontrada o no verificada.',
            ], 404);
        }

        return response()->json([
            'data' => new PropertyResource($property),
        ]);
    }

    /**
     * Get property availability ranges / blocked dates.
     */
    public function propertyAvailability(int $id, Request $request): JsonResponse
    {
        $property = Property::published()->findOrFail($id);

        $query = PropertyAvailability::where('property_id', $property->id);

        if ($startDate = $request->query('start_date')) {
            $query->where('date', '>=', $startDate);
        }

        if ($endDate = $request->query('end_date')) {
            $query->where('date', '<=', $endDate);
        }

        $availabilities = $query->orderBy('date')->get(['date', 'status']);

        return response()->json([
            'data' => $availabilities,
        ]);
    }
}
