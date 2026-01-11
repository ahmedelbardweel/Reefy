<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConsultationController;
use App\Http\Controllers\Api\CropController;

Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::middleware('auth:sanctum')->group( function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('profile', [AuthController::class, 'profile']);
    
    // Farmer Routes
    Route::apiResource('crops', CropController::class);

    // Consultation Routes
    Route::get('consultations', [ConsultationController::class, 'index']);
    Route::post('consultations', [ConsultationController::class, 'store']); // Farmer Create
    Route::get('consultations/{id}', [ConsultationController::class, 'show']);
    Route::post('consultations/{id}/reply', [ConsultationController::class, 'reply']); // Expert Reply
});
