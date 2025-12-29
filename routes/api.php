<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\RecordController;
use App\Http\Controllers\Api\FlowController;





Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        // Route::get('/countries', [LocationController::class, 'countries']);
        // Route::get('/states', [LocationController::class, 'states']);
        // Route::get('/cities', [LocationController::class, 'cities']);
        Route::get('/statistics/counts', [StatisticsController::class, 'counts']);
        Route::get('/statistics', [StatisticsController::class, 'index']);
        //Projects route for volunteer App
        Route::get('/projects/by-program/{programId}', [ProjectController::class, 'byProgram']);
        Route::get('/projects/getAllProjects', [ProjectController::class, 'getAllProjects']);
        // Patient
        Route::get('/patients/by-program/{programId}', [PatientController::class, 'byProgram']);
        Route::post('/patients', [PatientController::class, 'store']); // Create
        Route::post('/patients/{patient}', [PatientController::class, 'update']); // Update

        //Apointments
        Route::get('/appointments/upcoming', [AppointmentController::class, 'upcoming']);
        Route::get('/appointments/completed', [AppointmentController::class, 'completed']);

        // locations
        Route::get('/locations/countries', [LocationController::class, 'countries']);
        Route::get('/locations/states', [LocationController::class, 'states']);
        Route::get('/locations/cities', [LocationController::class, 'cities']);
        // Record APIs
        Route::post('/records', [RecordController::class, 'store']);
        Route::post('/records/{record}', [RecordController::class, 'update']);
        Route::get('/records/by-patient/{patientId}', [RecordController::class, 'byPatient']);

        // Support APIs for dropdowns
        Route::get('/records/doctors', [RecordController::class, 'doctors']);
        Route::get('/records/gps', [RecordController::class, 'gps']);
        Route::get('/records/{recordId}/appointments', [RecordController::class, 'appointments']);

        // Questionnaire Flow APIs
        Route::get('/flow/symptoms', [FlowController::class, 'symptoms']);
        Route::get('/flow/questions/first/{symptomId}', [FlowController::class, 'firstQuestion']);
        Route::post('/flow/questions/next', [FlowController::class, 'nextQuestion']);

        //resourses should be last otherwise it may override other routes
        Route::apiResource('programs', ProgramController::class);
        Route::apiResource('projects', ProjectController::class);
    });
});
