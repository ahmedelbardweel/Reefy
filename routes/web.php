<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmerProfileController;
use App\Http\Controllers\CropController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});



Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin')->get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::middleware('role:farmer')->group(function () {
        Route::get('/farmer/dashboard', [App\Http\Controllers\Farmer\FarmerDashboardController::class, 'index'])->name('farmer.dashboard');

        Route::get('/farmer/profile/verification', [FarmerProfileController::class, 'edit'])->name('farmer.profile.edit');
        Route::patch('/farmer/profile/verification', [FarmerProfileController::class, 'update'])->name('farmer.profile.update');
        
        Route::resource('crops', CropController::class);
        Route::post('/crops/{crop}/tasks', [CropController::class, 'storeTask'])->name('crops.tasks.store');
        Route::post('/crops/{crop}/update-growth', [CropController::class, 'updateGrowth'])->name('crops.updateGrowth');
        Route::match(['POST', 'PUT'], '/tasks/{task}/complete', [CropController::class, 'completeTask'])->name('tasks.complete');

        // Specialized Systems
        Route::controller(App\Http\Controllers\Farmer\FarmerSystemController::class)->group(function () {
            Route::get('/farmer/systems/irrigation', 'irrigation')->name('farmer.systems.irrigation');
            Route::get('/farmer/systems/treatment', 'treatment')->name('farmer.systems.treatment');
            Route::get('/farmer/systems/harvesting', 'harvesting')->name('farmer.systems.harvesting');
        });
    });

    Route::middleware('role:expert')->get('/expert/dashboard', [App\Http\Controllers\Expert\ExpertDashboardController::class, 'index'])->name('expert.dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Notifications
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [App\Http\Controllers\NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::get('/notifications/upcoming-tasks', [App\Http\Controllers\NotificationController::class, 'getUpcomingTasks'])->name('notifications.upcoming');
    Route::post('/notifications/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.readAll');

    // Community Actions
    Route::controller(App\Http\Controllers\CommunityController::class)->group(function () {
        Route::post('/community/post', 'store')->name('community.store');
        Route::post('/community/post/{post}/like', 'toggleLike')->name('community.like');
        Route::post('/community/post/{post}/comment', 'storeComment')->name('community.comment');
        Route::delete('/community/post/{post}', 'destroy')->name('community.destroy');
    });

    // Consultations (Farmer)
    Route::resource('consultations', App\Http\Controllers\ConsultationController::class);

    // Consultations (Expert)
    Route::middleware('role:expert')->group(function () {
        Route::get('/expert/consultations', [App\Http\Controllers\ConsultationController::class, 'expertIndex'])->name('expert.consultations.index');
        Route::post('/consultations/{consultation}/answer', [App\Http\Controllers\ConsultationController::class, 'answer'])->name('consultations.answer');
    });
});


Route::get('/farmer/profile/{id}', [App\Http\Controllers\FarmerProfileController::class, 'show'])->name('farmer.profile.public');

// Public Community access
Route::get('/community', [App\Http\Controllers\CommunityController::class, 'index'])->name('community.index');
Route::get('/community/post/{post}', [App\Http\Controllers\CommunityController::class, 'show'])->name('community.show');

require __DIR__.'/auth.php';
