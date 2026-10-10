<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CatalogController;
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
    Route::get('/properties', [CatalogController::class, 'properties'])->name('properties.index');
    Route::get('/properties/{slug}', [CatalogController::class, 'propertyDetail'])->name('properties.show');
    Route::get('/properties/{id}/availability', [CatalogController::class, 'propertyAvailability'])->name('properties.availability');
    Route::get('/experiences', [ExperienceController::class, 'index'])->name('experiences.index');
    Route::get('/experiences/{slug}', [ExperienceController::class, 'show'])->name('experiences.show');
});
