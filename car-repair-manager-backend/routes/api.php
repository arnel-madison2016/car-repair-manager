<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// for users registration
Route::post('/register', [AuthController::class, 'register']);

// for users log-in
Route::post('/login', [AuthController::class, 'login']);

// user account verify with verify_otp code
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');

// resend verification email
Route::get('/resend-verification-email/{id}', [AuthController::class, 'resendVerification'])->name('resend.verification.email');

// protected routes, only for verify users
Route::middleware('auth:sanctum')->group(callback: function () {

    // for users logged out
    Route::post('logout', [AuthController::class, 'logout']);

    // send reset password code
    Route::post('/send-password-code', [AuthController::class, 'sendResetPasswordCode']);

    // reinitialize password
    Route::post('/reset-password', [AuthController::class, 'reset']);

    // show profile
    Route::get('/profile', [ProfileController::class, 'index']); //->middleware('auth');

});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
// });
