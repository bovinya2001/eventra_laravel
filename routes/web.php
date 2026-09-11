<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\RegistrationPaymentController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\EmailVerificationOtpController;

// Landing Page
Route::get('/', fn() => view('landing'))->name('landing');

//Dashboard Route
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

Route::middleware('auth')->group(function () {
    Route::post('/email/verify/otp', [EmailVerificationOtpController::class, 'verify'])
        ->middleware('throttle:6,1')
        ->name('verification.otp.verify');

    Route::post('/email/verify/send-otp', function (\Illuminate\Http\Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-otp-sent');
    })->middleware('throttle:6,1')->name('verification.otp.send');
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
    Route::get('/registrations/{registration}/checkout', [RegistrationPaymentController::class, 'checkout'])->name('registrations.checkout');
    Route::get('/registrations/{registration}/pass', [RegistrationPaymentController::class, 'pass'])->name('registrations.pass');
    Route::get('/registrations/{registration}/qr', [RegistrationPaymentController::class, 'qr'])->name('registrations.qr');
    Route::post('/registrations/{registration}/stripe/intent', [StripePaymentController::class, 'intent'])->name('stripe.intent');
    Route::get('/registrations/{registration}/stripe/completed', [StripePaymentController::class, 'completed'])->name('stripe.completed');
});

Route::post('/stripe/webhook', [StripePaymentController::class, 'webhook'])->name('stripe.webhook');

Route::get('/venue-pass/{uuid}', [RegistrationPaymentController::class, 'verify'])
    ->middleware('signed')
    ->name('venue-pass.verify');

// Admin Auth Routes (no middleware — public login page)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Routes
    Route::middleware(['auth:admin', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('events', AdminEventController::class);
    });
});
