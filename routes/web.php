<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FarmerProfileController;
use App\Http\Controllers\CropController;
use App\Http\Controllers\LanguageController;

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

Route::get('/lang/{locale}', [LanguageController::class, 'switch'])->name('lang.switch');

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
        
        Route::get('crops/suggestions-data', [CropController::class, 'suggestionsData'])->name('crops.suggestions.data');
        Route::resource('crops', CropController::class);
        Route::post('/crops/{crop}/tasks', [CropController::class, 'storeTask'])->name('crops.tasks.store');
        Route::post('/crops/{crop}/update-growth', [CropController::class, 'updateGrowth'])->name('crops.updateGrowth');
        Route::match(['POST', 'PUT'], '/tasks/{task}/complete', [CropController::class, 'completeTask'])->name('tasks.complete');

        // Specialized Systems
        Route::controller(App\Http\Controllers\Farmer\FarmerSystemController::class)->group(function () {
            Route::get('/farmer/systems/irrigation', 'irrigation')->name('farmer.systems.irrigation');
            Route::get('/farmer/systems/treatment', 'treatment')->name('farmer.systems.treatment');
            Route::get('/farmer/systems/harvesting', 'harvesting')->name('farmer.systems.harvesting');
            Route::get('/farmer/systems/harvesting/export', 'exportHarvesting')->name('farmer.systems.harvesting.export');
        });
    });

    Route::middleware('role:expert')->get('/expert/dashboard', [App\Http\Controllers\Expert\ExpertDashboardController::class, 'index'])->name('expert.dashboard');

    Route::get('/profile/view', function() {
        return view('profile.show');
    })->name('profile.show');

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
        
        // Expert Tips
        Route::post('/expert/tips', [App\Http\Controllers\ExpertTipController::class, 'store'])->name('expert.tips.store');
        Route::put('/expert/tips/{expertTip}', [App\Http\Controllers\ExpertTipController::class, 'update'])->name('expert.tips.update');
        Route::delete('/expert/tips/{expertTip}', [App\Http\Controllers\ExpertTipController::class, 'destroy'])->name('expert.tips.destroy');
    });
});


Route::get('/farmer/profile/{id}', [App\Http\Controllers\FarmerProfileController::class, 'show'])->name('farmer.profile.public');

// Public Community access
Route::get('/community', [App\Http\Controllers\CommunityController::class, 'index'])->name('community.index');
Route::get('/community/post/{post}', [App\Http\Controllers\CommunityController::class, 'show'])->name('community.show');

// Temporary route for Render deployment to run migrations without shell access
use Illuminate\Support\Facades\Artisan;
Route::get('/run-migrations-secret-url', function () {
    Artisan::call('migrate', ['--force' => true]);
    return "Migration Completed Successfully: " . Artisan::output();
});

// ---------- PREVIEW ROUTES (NO AUTH REQUIRED, MOCKS AUTH & DATA) ----------
Route::prefix('preview')->name('preview.')->middleware(function ($request, $next) {
    if (!auth()->check()) {
        $dummyUser = new \App\Models\User([
            'id' => 9999,
            'name' => 'Designer Preview',
            'email' => 'designer@preview.com',
            'role' => 'farmer'
        ]);
        $dummyUser->setRelation('crops', collect([]));
        auth()->setUser($dummyUser);
    }
    return $next($request);
})->group(function () {
    Route::get('/farmer/dashboard', function () { return view('farmer.dashboard-preview'); })->name('farmer.dashboard');
    Route::get('/admin/dashboard', function () { return view('admin.dashboard'); })->name('admin.dashboard');
    Route::get('/expert/dashboard', function () { return view('expert.dashboard'); })->name('expert.dashboard');
    
    // Community
    Route::get('/community', function () { 
        $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(collect([]), 0, 10, 1, ['path' => url('/')]);
        return view('community.index', ['posts' => $emptyPaginator, 'trendingTags' => collect()]); 
    })->name('community.index');
    
    Route::get('/community/post', function () { 
        $dummyPost = new \App\Models\CommunityPost();
        $dummyPost->id = 1;
        $dummyPost->content = 'Sample content';
        $dummyPost->user_id = 1;
        $dummyPost->created_at = now();
        $dummyPost->setRelation('user', new \App\Models\User(['name' => 'Designer']));
        $dummyPost->setRelation('comments', collect([]));
        $dummyPost->setRelation('likes', collect([]));
        return view('community.show', ['post' => $dummyPost]); 
    })->name('community.show');

    // Crops & Systems
    Route::get('/crops', function () { 
        $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(collect([]), 0, 10, 1, ['path' => url('/')]);
        return view('crops.index', ['crops' => $emptyPaginator]); 
    })->name('crops.index');
    
    Route::get('/crops/create', function () { return view('crops.create'); })->name('crops.create');
    
    Route::get('/systems/irrigation', function () { 
        $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(collect([]), 0, 10, 1, ['path' => url('/')]);
        return view('farmer.systems.irrigation', ['tasks' => $emptyPaginator, 'totalWater' => 0]); 
    })->name('systems.irrigation');
    
    Route::get('/systems/treatment', function () { 
        $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(collect([]), 0, 10, 1, ['path' => url('/')]);
        return view('farmer.systems.treatment', ['tasks' => $emptyPaginator]); 
    })->name('systems.treatment');
    
    Route::get('/systems/harvesting', function () { 
        $emptyPaginator = new \Illuminate\Pagination\LengthAwarePaginator(collect([]), 0, 10, 1, ['path' => url('/')]);
        return view('farmer.systems.harvesting', ['tasks' => $emptyPaginator, 'totalYield' => 0]); 
    })->name('systems.harvesting');

    // Consultations
    Route::get('/consultations', function () { return view('consultations.index', ['consultations' => collect([])]); })->name('consultations.index');
    Route::get('/consultations/create', function () { return view('consultations.create'); })->name('consultations.create');
    
    // Profiles
    Route::get('/profile', function () { return view('profile.show'); })->name('profile.show');
    Route::get('/profile/edit', function () { return view('profile.edit', ['user' => auth()->user()]); })->name('profile.edit');
});

require __DIR__.'/auth.php';
