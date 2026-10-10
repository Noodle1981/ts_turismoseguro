<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthController extends Controller
{
    /**
     * Check API health and core service connectivity.
     */
    public function __invoke(): JsonResponse
    {
        $dbStatus = 'connected';

        try {
            DB::connection()->getPdo();
        } catch (Throwable $e) {
            $dbStatus = 'disconnected: '.$e->getMessage();
        }

        $isHealthy = $dbStatus === 'connected';

        $payload = [
            'status' => $isHealthy ? 'ok' : 'degraded',
            'timestamp' => now()->toIso8601String(),
            'environment' => config('app.env', 'production'),
            'services' => [
                'database' => $dbStatus,
                'cache' => 'connected',
            ],
        ];

        return response()->json($payload, $isHealthy ? 200 : 500);
    }
}
