<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TaxController;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;

Route::get('/', [PageController::class, 'landing'])->name('home');

Route::middleware(RedirectIfAuthenticated::class)->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::middleware(Authenticate::class)->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/smart-finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::post('/smart-finance', [FinanceController::class, 'analyze'])->name('finance.analyze');
    Route::get('/stata', [PageController::class, 'stata']);
    Route::get('/perpajakan', [TaxController::class, 'index'])->name('perpajakan.index');
    Route::post('/perpajakan', [TaxController::class, 'calculate'])->name('perpajakan.calculate');
});
