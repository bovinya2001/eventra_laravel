<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\EventApiController;
use App\Http\Controllers\Api\RegistrationApiController;

/*
|--------------------------------------------------------------------------
| Public Routes — No token needed
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->middleware('throttle:api-public')->group(function () {

    // Auth
    Route::post('/login', [AuthApiController::class, 'login'])->middleware('throttle:api-auth');
    Route::post('/register', [AuthApiController::class, 'register'])->middleware('throttle:api-registration');

    // Public Events
    Route::get('/events', [EventApiController::class, 'index']);
    Route::get('/events/{event}', [EventApiController::class, 'show']);
    Route::get('/events/search/{query}', [EventApiController::class, 'search']);
    Route::get('/events/upcoming/list', [EventApiController::class, 'upcoming']);
    Route::get('/events/stats/summary', [EventApiController::class, 'stats']);

    /*
    |--------------------------------------------------------------------------
    | Protected Routes — Bearer token required
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/logout', [AuthApiController::class, 'logout'])->middleware('abilities:profile:read');
        Route::get('/me', [AuthApiController::class, 'me'])->middleware('abilities:profile:read');
        Route::put('/me/update', [AuthApiController::class, 'update'])->middleware('abilities:profile:write');
        Route::post('/email/verification-notification', function (Request $request) {
            if ($request->user()->hasVerifiedEmail()) {
                return response()->json(['status' => 'success', 'message' => 'Email address is already verified.']);
            }

            $request->user()->sendEmailVerificationNotification();

            return response()->json(['status' => 'success', 'message' => 'Verification link sent.']);
        })->middleware(['abilities:profile:read', 'throttle:6,1']);

        Route::middleware('verified')->group(function () {
            // Registrations and venue passes are available only to verified accounts.
            Route::get('/my-registrations', [RegistrationApiController::class, 'index'])->middleware('abilities:registrations:read');
            Route::post('/events/{event}/register', [RegistrationApiController::class, 'store'])->middleware('abilities:registrations:write');
            Route::get('/my-registrations/{registration}/qr', [RegistrationApiController::class, 'qr'])->middleware('abilities:passes:read');
            Route::delete('/events/{event}/cancel', [RegistrationApiController::class, 'destroy'])->middleware('abilities:registrations:write');
            Route::get('/my-registrations/{registration}', [RegistrationApiController::class, 'show'])->middleware('abilities:registrations:read');
        });
    });
});
