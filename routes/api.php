<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\UnitController;
use App\Http\Controllers\Api\DriverController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-device-otp', [AuthController::class, 'verifyDeviceOtp']);
Route::post('/send-device-otp', [AuthController::class, 'sendDeviceOtp']);
Route::post('/forgot-password', [AuthController::class, 'sendResetOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Core Resources
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/units', [UnitController::class, 'index']);
    Route::post('/units', [UnitController::class, 'store']);
    Route::get('/units/{id}', [UnitController::class, 'show']);
    Route::get('/drivers', [DriverController::class, 'index']);
    
    // Boundary & Financials
    Route::get('/boundaries', [\App\Http\Controllers\Api\BoundaryController::class, 'index']);

    // Live Tracking (GPS)
    Route::get('/live-tracking/units', [\App\Http\Controllers\LiveTrackingController::class, 'getUnitsLive']);
    Route::get('/live-tracking/unit/{id}', [\App\Http\Controllers\LiveTrackingController::class, 'getUnitLocation']);
    Route::post('/live-tracking/engine', [\App\Http\Controllers\LiveTrackingController::class, 'engineControl']);

    // Super Admin / Owner Panel
    Route::prefix('super-admin')->group(function () {
        Route::get('/overview', [\App\Http\Controllers\SuperAdminController::class, 'indexJson']);
        Route::get('/audit', [\App\Http\Controllers\SuperAdminController::class, 'loginHistory']);
        Route::post('/staff', [\App\Http\Controllers\SuperAdminController::class, 'storeStaff']);
        Route::post('/users/{id}/approve', [\App\Http\Controllers\SuperAdminController::class, 'approveUser']);
        Route::post('/users/{id}/reject', [\App\Http\Controllers\SuperAdminController::class, 'rejectUser']);
        Route::post('/users/{id}/toggle-disable', [\App\Http\Controllers\SuperAdminController::class, 'toggleDisable']);
        Route::post('/users/{id}/page-access', [\App\Http\Controllers\SuperAdminController::class, 'updatePageAccess']);
        Route::post('/users/{id}/archive', [\App\Http\Controllers\SuperAdminController::class, 'archiveUser']);
        Route::post('/users/{id}/restore', [\App\Http\Controllers\SuperAdminController::class, 'restoreUser']);
        Route::delete('/users/{id}', [\App\Http\Controllers\SuperAdminController::class, 'deleteUser']);
        Route::put('/users/{id}/update', [\App\Http\Controllers\SuperAdminController::class, 'updateUser']);
        Route::post('/archive-password', [\App\Http\Controllers\SuperAdminController::class, 'updateArchivePassword']);
        
        // Roles
        Route::post('/roles', [\App\Http\Controllers\SuperAdminController::class, 'storeRole']);
        Route::delete('/roles/{id}/archive', [\App\Http\Controllers\SuperAdminController::class, 'archiveRole']);
        Route::post('/roles/{id}/restore', [\App\Http\Controllers\SuperAdminController::class, 'restoreRole']);
        Route::delete('/roles/{id}', [\App\Http\Controllers\SuperAdminController::class, 'deleteRole']);
        
        // Incident Classifications
        Route::post('/classifications', [\App\Http\Controllers\SuperAdminController::class, 'storeClassification']);
        Route::delete('/classifications/{id}/archive', [\App\Http\Controllers\SuperAdminController::class, 'archiveClassification']);
        Route::post('/classifications/{id}/restore', [\App\Http\Controllers\SuperAdminController::class, 'restoreClassification']);
        Route::delete('/classifications/{id}', [\App\Http\Controllers\SuperAdminController::class, 'deleteClassification']);
    });
});
