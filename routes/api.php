<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ReclamationController;
use App\Http\Controllers\ChartController;


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/users', [AuthController::class, 'getUsers']);
    Route::delete('/users/{id}', [AuthController::class, 'destroy']);
    Route::get('/profile', function (Request $request) {
        return response()->json($request->user());
    });

    // Routes protégées par rôles
    Route::middleware('role:admin')->get('/admin', function () {
        return response()->json(['message' => 'Admin dashboard']);
    });

    Route::middleware('role:client')->get('/client', function () {
        return response()->json(['message' => 'Client dashboard']);
    });

    Route::middleware('role:freelancer')->get('/freelancer', function () {
        return response()->json(['message' => 'Freelancer dashboard']);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/profile', [ProfileController::class, 'store']);
        Route::get('/profile/{id}', [ProfileController::class, 'show']);
        Route::put('/profile/{id}', [ProfileController::class, 'update']);
        Route::get('/freelancers', [ProfileController::class, 'index']);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/projects', [ProjectController::class, 'store']);
        Route::get('/projects', [ProjectController::class, 'index']);
        Route::get('/projects/summary', [ProjectController::class, 'getSummary']);
        Route::get('/projects/{id}', [ProjectController::class, 'show']);
        Route::put('/projects/{id}/status', [ProjectController::class, 'updateStatus']);
        Route::put('/projects/{id}', [ProjectController::class, 'update']);
        Route::get('/myProjects', [ProjectController::class, 'myProjects']);
        Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
       

    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/messages', [MessageController::class, 'store']);
        Route::get('/messages/{conversation_id}', [MessageController::class, 'index']);
        Route::get('/conversations', [MessageController::class, 'conversations']);

    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/payments', [PaymentController::class, 'store']);
        Route::get('/payments/history', [PaymentController::class, 'index']);
        Route::put('/payments/{id}/status', [PaymentController::class, 'updateStatus']);
        Route::get('/payments/{id}', [PaymentController::class, 'show']);
        Route::get('/payments/project/{projectId}', [PaymentController::class, 'showByProject']);

    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/reviews', [ReviewController::class, 'store']);
        Route::get('/reviews/{freelancer_id}', [ReviewController::class, 'index']);
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/applications', [ApplicationController::class, 'store']); 
        Route::get('/applications/{project_id}', [ApplicationController::class, 'index']); 
        Route::put('/applications/{id}/status', [ApplicationController::class, 'updateStatus']); 
        Route::get('/my-applications', [ApplicationController::class, 'myApplications']);
        Route::get('/application_project', [ApplicationController::class, 'applicationsByProject']);
    });
    Route::middleware('auth:sanctum')->group(function () {
    Route::get('/reclamations', [ReclamationController::class, 'index']);
    Route::post('/reclamations', [ReclamationController::class, 'store']);
    Route::get('/reclamations/{reclamation}', [ReclamationController::class, 'show']);
    Route::put('/reclamations/{reclamation}', [ReclamationController::class, 'update']);
    Route::delete('/reclamations/{reclamation}', [ReclamationController::class, 'destroy']);
    Route::put('/reclamations/{id}', [ReclamationController::class, 'updateStatus']);
    });
    Route::get('/test-db', function () {
        try {
            DB::connection()->getPdo();
            return response()->json(['message' => 'Connexion réussie à la base de données'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Impossible de se connecter à la base de données', 'message' => $e->getMessage()], 500);
        }
    });
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/charts/status', [ChartController::class, 'projectStatusStats']);
        Route::get('/charts/deadlines', [ChartController::class, 'projectDeadlineStats']);
    });
});
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

