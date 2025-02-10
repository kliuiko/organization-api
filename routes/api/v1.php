<?php

use App\Http\Controllers\Api\V1\OrganizationController;
use App\Http\Middleware\ApiKeyMiddleware;
use Illuminate\Support\Facades\Route;

Route::middleware(ApiKeyMiddleware::class)->group(function () {
    Route::prefix('v1')->group(function () {
        Route::prefix('organizations')->group(function () {
            Route::get('/', [OrganizationController::class, 'index']);

            Route::get('/by-building/{id}', [OrganizationController::class, 'byBuilding']);
            Route::get('/by-activity/{id}', [OrganizationController::class, 'byActivity']);

            Route::get('/by-radius', [OrganizationController::class, 'byRadius']);
            Route::get('/by-bounds', [OrganizationController::class, 'byBounds']);

            Route::get('/search', [OrganizationController::class, 'search']);

            Route::get('/{id}', [OrganizationController::class, 'show']);
        });

    });
});
