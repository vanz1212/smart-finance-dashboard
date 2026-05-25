<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TaxController;

Route::get('/', [PageController::class, 'landing']);
Route::get('/smart-finance', [FinanceController::class, 'index'])->name('finance.index');
Route::post('/smart-finance', [FinanceController::class, 'analyze'])->name('finance.analyze');
Route::get('/stata', [PageController::class, 'stata']);
Route::get('/perpajakan', [TaxController::class, 'index'])->name('perpajakan.index');
Route::post('/perpajakan', [TaxController::class, 'calculate'])->name('perpajakan.calculate');
