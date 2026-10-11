<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CatalogController;
use App\Http\Controllers\Api\V1\ClientPropertyController;
use App\Http\Controllers\Api\V1\ExperienceController;
use App\Http\Controllers\Api\V1\HealthController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', HealthController::class)->name('api.v1.health');

    // Módulo de Autenticación y Roles
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('auth.login');
        Route::post('/register/rent', [AuthController::class, 'registerRent'])->middleware('throttle:10,1')->name('auth.register.rent');
        Route::post('/register/client', [AuthController::class, 'registerClient'])->middleware('throttle:10,1')->name('auth.register.client');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:5,1')->name('auth.forgot-password');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1')->name('auth.reset-password');

        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
            Route::get('/me', [AuthController::class, 'me'])->name('auth.me');
        });
    });

    // Catálogo Público (Turistas y Habitantes Locales)
    Route::get('/departments', [CatalogController::class, 'departments'])->name('departments.index');
    Route::get('/departments/{department}/localities', function (string $department) {
        $dept = \App\Models\Department::where('slug', $department)
            ->orWhere('id', is_numeric($department) ? (int)$department : null)
            ->firstOrFail();

        $localities = $dept->localities()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'department_id', 'name', 'slug', 'latitude', 'longitude', 'postal_code']);

        return response()->json(['data' => $localities]);
    })->name('departments.localities');
    Route::get('/properties', [CatalogController::class, 'properties'])->name('properties.index');
    Route::get('/properties/{slug}', [CatalogController::class, 'propertyDetail'])->name('properties.show');
    Route::get('/properties/{id}/availability', [CatalogController::class, 'propertyAvailability'])->name('properties.availability');
    Route::get('/experiences', [ExperienceController::class, 'index'])->name('experiences.index');
    Route::get('/experiences/{slug}', [ExperienceController::class, 'show'])->name('experiences.show');
    Route::get('/events', function (\Illuminate\Http\Request $request) {
        $department = $request->query('department');
        $events = \App\Models\Event::where('is_active', true)
            ->forDepartment($department)
            ->orderBy('start_date', 'asc')
            ->get();
        return response()->json(['data' => $events]);
    })->name('events.index');

    // Panel del Anfitrión (Rol: client o super_admin)
    Route::middleware(['auth:sanctum', 'role:client,super_admin'])->prefix('client')->group(function () {
        Route::get('/properties', [ClientPropertyController::class, 'index'])->name('client.properties.index');
        Route::post('/properties', [ClientPropertyController::class, 'store'])->name('client.properties.store');
        Route::get('/properties/{id}', [ClientPropertyController::class, 'show'])->name('client.properties.show');
        Route::put('/properties/{id}', [ClientPropertyController::class, 'update'])->name('client.properties.update');
        Route::get('/properties/{id}/availability', [ClientPropertyController::class, 'availability'])->name('client.properties.availability');
        Route::post('/properties/{id}/availability', [ClientPropertyController::class, 'updateAvailability'])->name('client.properties.update-availability');
        Route::post('/properties/{id}/availability/toggle-date', [ClientPropertyController::class, 'toggleAvailabilityDate'])->name('client.properties.toggle-date');
        Route::get('/properties/{id}/tenants', [ClientPropertyController::class, 'tenants'])->name('client.properties.tenants');
        Route::post('/bookings/{id}/rate', [ClientPropertyController::class, 'rateBooking'])->name('client.bookings.rate');
        Route::post('/bookings/{id}/observation', [ClientPropertyController::class, 'saveBookingObservation'])->name('client.bookings.observation');
        Route::post('/verifications/request', [ClientPropertyController::class, 'requestVerification'])->name('client.verifications.request');
    });

    // Panel Staff / Mesa de Supervisión y Validadores (Rol: validator, supervisor, admin, super_admin)
    Route::middleware(['auth:sanctum', 'role:validator,supervisor,admin,super_admin'])->prefix('staff')->group(function () {
        Route::get('/verifications', [\App\Http\Controllers\Api\V1\StaffVerificationController::class, 'index'])->name('staff.verifications.index');
        Route::put('/verifications/{id}/assign', [\App\Http\Controllers\Api\V1\StaffVerificationController::class, 'assignValidator'])->name('staff.verifications.assign');
    });
});
