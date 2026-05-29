<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\EventApiController;
use App\Http\Controllers\Api\RegistrationApiController;

/*
|--------------------------------------------------------------------------
| Public Routes — No token needed
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {

    // Auth
    Route::post('/login', [AuthApiController::class, 'login']);
    Route::post('/register', [AuthApiController::class, 'register']);

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
        Route::post('/logout', [AuthApiController::class, 'logout']);
        Route::get('/me', [AuthApiController::class, 'me']);
        Route::put('/me/update', [AuthApiController::class, 'update']);

        // Registrations
        Route::get('/my-registrations', [RegistrationApiController::class, 'index']);
        Route::post('/events/{event}/register', [RegistrationApiController::class, 'store']);
        Route::delete('/events/{event}/cancel', [RegistrationApiController::class, 'destroy']);
        Route::get('/my-registrations/{registration}', [RegistrationApiController::class, 'show']);
    });
});