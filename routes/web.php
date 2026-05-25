<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TaxController;

Route::get('/', [PageController::class, 'landing']);
Route::get('/smart-finance', [PageController::class, 'smartFinance']);
Route::get('/stata', [PageController::class, 'stata']);
Route::get('/perpajakan', [TaxController::class, 'index'])->name('perpajakan.index');
Route::post('/perpajakan', [TaxController::class, 'calculate'])->name('perpajakan.calculate');
