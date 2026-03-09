<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmerProfileController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\ConsultationController;
use App\Http\Controllers\ExpertTipController;
use App\Http\Controllers\Farmer\FarmerDashboardController;
use App\Http\Controllers\Farmer\FarmerSystemController;
use App\Http\Controllers\Expert\ExpertDashboardController;
use App\Http\Controllers\GeneralController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ─── Public Routes ────────────────────────────────────────────────────────────

Route::get('/', [GeneralController::class, 'welcome']);

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

Route::get('/theme/toggle', [GeneralController::class, 'toggleTheme'])->name('theme.toggle');

Route::get('/community', [CommunityController::class, 'index'])->name('community.index');
Route::get('/community/post/{post}', [CommunityController::class, 'show'])->name('community.show');

Route::get('/farmer/profile/{id}', [FarmerProfileController::class, 'show'])->name('farmer.profile.public');

// ─── Authenticated Routes ──────────────────────────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile/view', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications
    Route::controller(NotificationController::class)->prefix('/notifications')->group(function () {
        Route::get('/', 'index')->name('notifications.index');
        Route::get('/unread', 'getUnread')->name('notifications.unread');
        Route::get('/upcoming-tasks', 'getUpcomingTasks')->name('notifications.upcoming');
        Route::post('/{notification}/read', 'markAsRead')->name('notifications.read');
        Route::post('/read-all', 'markAllAsRead')->name('notifications.readAll');
        Route::get('/notifications/unread-count',
                                            [NotificationController::class, 'getUnreadCount']
          )->middleware('auth');

    });

    // Community Actions (Authenticated)
    Route::controller(CommunityController::class)->prefix('/community')->group(function () {
        Route::post('/post', 'store')->name('community.store');
        Route::post('/post/{post}/like', 'toggleLike')->name('community.like');
        Route::post('/post/{post}/comment', 'storeComment')->name('community.comment');
        Route::delete('/post/{post}', 'destroy')->name('community.destroy');
    });

    // Consultations
    Route::resource('consultations', ConsultationController::class);

    // ─── Admin ────────────────────────────────────────────────────────────────
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [GeneralController::class, 'adminDashboard'])->name('admin.dashboard');
    });

    // ─── Farmer ───────────────────────────────────────────────────────────────
    Route::middleware('role:farmer')->group(function () {

        // Farmer Dashboard & Profile
        Route::get('/farmer/dashboard', [FarmerDashboardController::class, 'index'])->name('farmer.dashboard');
        Route::get('/farmer/profile/verification', [FarmerProfileController::class, 'edit'])->name('farmer.profile.edit');
        Route::patch('/farmer/profile/verification', [FarmerProfileController::class, 'update'])->name('farmer.profile.update');

        // Crops
        Route::get('crops/suggestions-data', [CropController::class, 'getAjaxSuggestions'])->name('crops.ajax_suggestions');
        Route::resource('crops', CropController::class);
        Route::delete('/crops/images/{image}', [CropController::class, 'destroyImage'])->name('crops.images.destroy');
        Route::post('/crops/{crop}/tasks', [CropController::class, 'storeTask'])->name('crops.tasks.store');
        Route::post('/crops/{crop}/update-growth', [CropController::class, 'updateGrowth'])->name('crops.updateGrowth');
        Route::match(['POST', 'PUT'], '/tasks/{task}/complete', [CropController::class, 'completeTask'])->name('tasks.complete');

        // Specialized Systems
        Route::controller(FarmerSystemController::class)->prefix('/farmer/systems')->group(function () {
            Route::get('/irrigation', 'irrigation')->name('farmer.systems.irrigation');
            Route::get('/treatment', 'treatment')->name('farmer.systems.treatment');
            Route::get('/harvesting', 'harvesting')->name('farmer.systems.harvesting');
            Route::get('/harvesting/export', 'exportHarvesting')->name('farmer.systems.harvesting.export');
        });
    });

    // ─── Expert ───────────────────────────────────────────────────────────────
    Route::middleware('role:expert')->group(function () {

        Route::get('/expert/dashboard', [ExpertDashboardController::class, 'index'])->name('expert.dashboard');

        Route::get('/expert/consultations', [ConsultationController::class, 'expertIndex'])->name('expert.consultations.index');
        Route::post('/consultations/{consultation}/answer', [ConsultationController::class, 'answer'])->name('consultations.answer');

        Route::controller(ExpertTipController::class)->prefix('/expert/tips')->group(function () {
            Route::post('/', 'store')->name('expert.tips.store');
            Route::put('/{expertTip}', 'update')->name('expert.tips.update');
            Route::delete('/{expertTip}', 'destroy')->name('expert.tips.destroy');
        });
    });
});

require __DIR__.'/auth.php';
