<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'landing']);
Route::get('/smart-finance', [PageController::class, 'smartFinance']);
Route::get('/stata', [PageController::class, 'stata']);
