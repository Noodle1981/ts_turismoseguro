<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ExperienceResource;
use App\Models\Experience;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ExperienceController extends Controller
{
    /**
     * List verified experiences with optional filters.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Experience::where('is_verified', true)
            ->with(['department', 'images']);

        if ($department = $request->query('department')) {
            $query->whereHas('department', function ($q) use ($department) {
                $q->where('slug', $department)
                    ->orWhere('name', 'like', "%{$department}%");
            });
        }

        if ($category = $request->query('category')) {
            $query->where('category', 'like', "%{$category}%");
        }

        $experiences = $query->orderBy('featured', 'desc')
            ->orderBy('id', 'asc')
            ->paginate(12);

        return ExperienceResource::collection($experiences);
    }

    /**
     * Get experience details by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $experience = Experience::where('is_verified', true)
            ->with(['department', 'images', 'includes', 'recommendations'])
            ->where('slug', $slug)
            ->first();

        if (! $experience) {
            return response()->json([
                'message' => 'Experiencia no encontrada.',
            ], 404);
        }

        return response()->json([
            'data' => new ExperienceResource($experience),
        ]);
    }
}
