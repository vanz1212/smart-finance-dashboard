<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/auth/google', [AuthController::class, 'googleLogin']);

Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profile Routes
    Route::post('/profile/update', [ProfileController::class, 'updateProfile']);
    Route::post('/profile/change-email/request-otp', [ProfileController::class, 'requestEmailChangeOtp']);
    Route::post('/profile/change-email/verify-otp', [ProfileController::class, 'verifyEmailChangeOtp']);

    // Smart Finance Routes
    Route::get('/smart-finance', [\App\Http\Controllers\FinanceController::class, 'index']);
    Route::post('/smart-finance/analyze', [\App\Http\Controllers\FinanceController::class, 'analyze']);
    Route::delete('/smart-finance/{id}', [\App\Http\Controllers\FinanceController::class, 'destroy']);
    
    // Financial Targets Routes
    Route::get('/targets', [\App\Http\Controllers\FinancialTargetController::class, 'index']);
    Route::post('/targets', [\App\Http\Controllers\FinancialTargetController::class, 'store']);
    Route::get('/targets/{target}', [\App\Http\Controllers\FinancialTargetController::class, 'show']);
    Route::put('/targets/{target}', [\App\Http\Controllers\FinancialTargetController::class, 'update']);
    Route::delete('/targets/{target}', [\App\Http\Controllers\FinancialTargetController::class, 'destroy']);
    Route::post('/targets/{target}/deposit', [\App\Http\Controllers\FinancialTargetController::class, 'addDeposit']);
    Route::delete('/targets/deposit/{deposit}', [\App\Http\Controllers\FinancialTargetController::class, 'removeDeposit']);

    // Tax Routes
    Route::get('/tax', [\App\Http\Controllers\TaxController::class, 'index']);
    Route::post('/tax/calculate', [\App\Http\Controllers\TaxController::class, 'calculate']);
    Route::delete('/tax/{id}', [\App\Http\Controllers\TaxController::class, 'destroy']);

    // Stata Routes
    Route::get('/stata', [\App\Http\Controllers\StataController::class, 'index']);
    Route::post('/stata/import', [\App\Http\Controllers\StataController::class, 'import']);
    Route::post('/stata/command', [\App\Http\Controllers\StataController::class, 'run']);
    Route::delete('/stata/dataset', [\App\Http\Controllers\StataController::class, 'clear']);

    // Admin Users Routes
    Route::get('/admin/users', [\App\Http\Controllers\Api\AdminController::class, 'index']);
    Route::get('/admin/users/{id}', [\App\Http\Controllers\Api\AdminController::class, 'show']);
    Route::put('/admin/users/{id}/role', [\App\Http\Controllers\Api\AdminController::class, 'updateRole']);
    Route::put('/admin/users/{id}/status', [\App\Http\Controllers\Api\AdminController::class, 'toggleStatus']);
    Route::delete('/admin/users/{id}', [\App\Http\Controllers\Api\AdminController::class, 'destroy']);
});
