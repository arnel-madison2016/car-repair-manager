<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CarModelController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HandlingAppointmentController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\RepairSheetController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\VehiculeController;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\Pricing;
use App\Models\Service;
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

// list category
Route::get('/list-categories', [CategoryController::class, 'index']);

// list services
Route::get('/list-services', [ServiceController::class, 'index']);

// list manufacturers
Route::get('/list-manufacturers', [ManufacturerController::class, 'index']);

// list brands
Route::get('/list-brands', [BrandController::class, 'index']);

// list brands
Route::get('/list-cars-models', [CarModelController::class, 'index']);

// list pricing
Route::get('/list-prices', [PricingController::class, 'index']);

// register as customer
Route::post('/customer', [CustomerController::class, 'store']);

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

    //***************************** CATEGORIES SERVICES MANAGEMENT ************************* */
    Route::apiResource('/category', CategoryController::class);

    //***************************** SERVICES MANAGEMENT ************************* */
    Route::apiResource('/services', ServiceController::class);

    //***************************** MANUFACTURERS MANAGEMENT ************************* */
    Route::apiResource('/manufacturers', ManufacturerController::class);

    //***************************** BRANDS MANAGEMENT ************************* */
    Route::apiResource('/brands', BrandController::class);

    //***************************** BRANDS MANAGEMENT ************************* */
    Route::apiResource('/car-models', CarModelController::class);

    //***************************** PRICING MANAGEMENT ************************* */
    Route::apiResource('/pricing', PricingController::class);

    //***************************** CUSTOMERS MANAGEMENT ************************* */
    Route::apiResource('/customers', CustomerController::class);

    //***************************** VEHICULES MANAGEMENT ************************* */
    Route::apiResource('/vehicules', VehiculeController::class);

    //***************************** APPOINTMENTS MANAGEMENT ************************* */
    Route::apiResource('/appointments', AppointmentController::class);
    Route::post('/handle-appointment/{id}', [HandlingAppointmentController::class, 'handleAppointment']);
   
    //***************************** REPAIR SHEETS MANAGEMENT ************************* */
    Route::apiResource('/repairsheets', RepairSheetController::class);

    //***************************** TASKS MANAGEMENT ************************* */
    Route::apiResource('/tasks', TaskController::class);

    //***************************** PARAMETERS MANAGEMENT ************************* */
    Route::apiResource('/parameters', ParameterController::class);

});

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
// });
