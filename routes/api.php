<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\ProjectController;

Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::apiResource('programs', ProgramController::class);
     Route::apiResource('projects', ProjectController::class);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        
    Route::get('/countries', [LocationController::class, 'countries']);
    Route::get('/states', [LocationController::class, 'states']); // ?countryid=1
    Route::get('/cities', [LocationController::class, 'cities']); // ?stateid=1

    
   
    });
});
