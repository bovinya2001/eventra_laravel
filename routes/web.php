<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;

// Landing Page
Route::get('/', fn() => view('landing'))->name('landing');

//Dashboard Route
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// User Event Routes
Route::get('/events', [EventController::class, 'index'])->name('events.index');
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/events/{event}/register', [RegistrationController::class, 'store'])->name('events.register');
    Route::delete('/events/{event}/cancel', [RegistrationController::class, 'destroy'])->name('events.cancel');
    Route::get('/my-events', [RegistrationController::class, 'myEvents'])->name('my.events');
    Route::get('/favorites', [RegistrationController::class, 'favorites'])->name('favorites');
});

// Admin Auth Routes (no middleware — public login page)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('events', AdminEventController::class);
    });
});
