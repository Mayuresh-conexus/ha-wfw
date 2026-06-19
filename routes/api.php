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
use App\Http\Controllers\Api\QuestionBulkUploadController;
use App\Http\Controllers\Api\SymptomController;
use App\Http\Controllers\Api\SymptomBulkController;
use App\Http\Controllers\Api\MedicineController;
use App\Http\Controllers\Api\RoundRobinController;



Route::prefix('v1')->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:login');
    Route::get('/symptoms-with-questions', [SymptomController::class, 'getSymptomsWithQuestions']);
    Route::get('/health', function () {
        return response()->json([
            'success' => true,
            'status' => 'ok',
            'timestamp' => now()->toIso8601String(),
        ]);
    });

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
        Route::get('/patients/by-id/{patientId}', [PatientController::class, 'byId']); // Get patient by ID
        Route::get('/patients/list', [PatientController::class, 'list']); // List all patients
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
        Route::get('/records/by-project/{projectId}', [RecordController::class, 'byProject']); // New
        
        // Sync API
        Route::get('/sync/pull', [\App\Http\Controllers\Api\SyncController::class, 'pull']);
        Route::post('/sync/push', [\App\Http\Controllers\Api\SyncController::class, 'push']);

        // Support APIs for dropdowns
        Route::get('/records/doctors', [RecordController::class, 'doctors']);
        Route::get('/records/gps', [RecordController::class, 'gps']);
        Route::get('/records/{recordId}/appointments', [RecordController::class, 'appointments']);

        // Questionnaire Flow APIs
        Route::get('/flow/symptoms', [FlowController::class, 'symptoms']);
        Route::get('/flow/questions/first/{symptomId}', [FlowController::class, 'firstQuestion']);
        Route::post('/flow/questions/next', [FlowController::class, 'getQuestionById']);

        // Programs
        Route::get('/programs/list', [ProgramController::class, 'list']);
        Route::apiResource('programs', ProgramController::class);

        //Projects
        Route::get('/projects/by-program/{programId}', [ProjectController::class, 'byProgram']);

        // Bulk Question Upload
        Route::post('/questions/bulk', [QuestionBulkUploadController::class, 'store']);
        Route::post('/symptoms/bulk', [SymptomBulkController::class, 'store']);

        //resourses should be last otherwise it may override other routes
        // Route::apiResource('projects', ProjectController::class);

        //Medicines
        Route::post('/medicines/bulk', [MedicineController::class, 'bulkStore']);
        Route::get('/medicines', [MedicineController::class, 'index']);

        //Doctor by Patient count for respective program
        Route::get('/records/doctor-patient-count/{projectId}', [RoundRobinController::class, 'index']);

        //volunteer data after login
        Route::get('/volunteer/{volunteerId}', [RoundRobinController::class, 'volunteerDataById']);


    });
});
