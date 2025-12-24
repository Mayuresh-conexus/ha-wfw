<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\ProjectController;

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        Route::apiResource('programs', ProgramController::class);
        Route::apiResource('projects', ProjectController::class);

        Route::get('/countries', [LocationController::class, 'countries']);
        Route::get('/states', [LocationController::class, 'states']);
        Route::get('/cities', [LocationController::class, 'cities']);
    });
});
