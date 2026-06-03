<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TaxController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'landing'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/profile', [AuthController::class, 'profile'])->name('profile');

Route::get('/beranda', [PageController::class, 'landing'])->name('landing');
Route::get('/landing', function () {
    return view('page_selector');
})->name('page.selector');
Route::get('/smart-finance', [FinanceController::class, 'index'])->name('finance.index');
Route::post('/smart-finance', [FinanceController::class, 'analyze'])->name('finance.analyze');
Route::get('/stata', [PageController::class, 'stata'])->name('stata');
Route::get('/perpajakan', [TaxController::class, 'index'])->name('perpajakan.index');
Route::post('/perpajakan', [TaxController::class, 'calculate'])->name('perpajakan.calculate');
