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
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\CommunityController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\FarmerDashboardController;
use App\Http\Controllers\Api\FarmerSystemController;
use App\Http\Controllers\Api\ExpertDashboardController;
use App\Http\Controllers\Api\ExpertTipController;
use App\Http\Controllers\Api\FarmerProfileController;
use App\Http\Controllers\Api\ProfileController;

// Public Routes (Auth)
Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

// Protected Routes
Route::middleware('auth:sanctum')->group( function () {
    // Auth & Profile
    Route::post('logout', [AuthController::class, 'logout']);
    
    // User Profile API
    Route::get('profile', [ProfileController::class, 'index']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::post('profile/images', [ProfileController::class, 'updateImages']);
    
    Route::post('profile/fcm-token', [AuthController::class, 'updateFcmToken']);
    
    // Farmer Profile Update
    Route::post('farmer/profile/update', [FarmerProfileController::class, 'update']);

    // --- Farmer Core Features ---
    Route::apiResource('crops', CropController::class);
    Route::post('crops/{id}/logs', [CropController::class, 'recordLog']);
    
    // Tasks (Linked to Crops)
    Route::get('crops/{crop}/tasks', [TaskController::class, 'index']);
    Route::get('tasks/upcoming', [TaskController::class, 'upcoming']);
    Route::get('/tasks/overdue', [TaskController::class, 'overdue']);
    Route::post('crops/{crop}/tasks', [TaskController::class, 'store']);
    Route::post('tasks/{task}/complete', [TaskController::class, 'complete']);

    // --- Community ---
    Route::get('community/posts', [CommunityController::class, 'index']);
    Route::post('community/posts', [CommunityController::class, 'store']);
    Route::get('community/posts/{id}', [CommunityController::class, 'show']);
    Route::get('community/posts/{id}/comments', [CommunityController::class, 'getComments']);
    Route::post('community/posts/{id}/like', [CommunityController::class, 'like']);
    Route::post('community/posts/{id}/comment', [CommunityController::class, 'comment']);

    // --- Consultations ---
    Route::get('consultations', [ConsultationController::class, 'index']);
    Route::post('consultations', [ConsultationController::class, 'store']); // Farmer Create
    Route::get('consultations/{id}', [ConsultationController::class, 'show']);
    Route::post('consultations/{id}/reply', [ConsultationController::class, 'reply']); // Expert Reply

    // --- Notifications ---
    Route::get('notifications', [NotificationController::class, 'index']);
    Route::get('notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::post('notifications/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::post('notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    // --- Farmer Dashboard & Systems ---
    Route::prefix('farmer')->group(function () {
        Route::get('dashboard', [FarmerDashboardController::class, 'index']);
        
        Route::get('systems/irrigation', [FarmerSystemController::class, 'irrigation']);
        Route::get('systems/treatment', [FarmerSystemController::class, 'treatment']);
        Route::get('systems/harvesting', [FarmerSystemController::class, 'harvesting']);
    });

    // --- Expert Dashboard & Tips ---
    Route::prefix('expert')->group(function () {
        Route::get('dashboard', [ExpertDashboardController::class, 'index']);
        
        Route::apiResource('tips', ExpertTipController::class);
    });
});
