<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TaxController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/beranda');
Route::get('/beranda', [PageController::class, 'landing'])->name('home');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'register'])->name('signup.process');

Route::middleware(Authenticate::class)->group(function () {
    Route::get('/dashboard', function () {
        return auth()->user()?->role === 'admin'
            ? redirect()->route('dashboard.admin')
            : redirect()->route('dashboard.user');
    })->name('dashboard');

    Route::get('/dashboard/user', function () {
        return view('page_selector');
    })->middleware('role:user')->name('dashboard.user');

    Route::get('/dashboard/admin', [AdminUserController::class, 'index'])
        ->middleware('role:admin')
        ->name('dashboard.admin');

    Route::get('/dashboard/admin/users', [AdminUserController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.users.index');

    Route::get('/dashboard/admin/users/{user}', [AdminUserController::class, 'show'])
        ->middleware('role:admin')
        ->name('admin.users.show');

    Route::get('/dashboard/admin/users/{user}/edit', [AdminUserController::class, 'edit'])
        ->middleware('role:admin')
        ->name('admin.users.edit');

    Route::put('/dashboard/admin/users/{user}', [AdminUserController::class, 'update'])
        ->middleware('role:admin')
        ->name('admin.users.update');

    Route::delete('/dashboard/admin/users/{user}', [AdminUserController::class, 'destroy'])
        ->middleware('role:admin')
        ->name('admin.users.destroy');

    Route::get('/admin', function () {
        return redirect()->route('dashboard.admin');
    })->middleware('role:admin')->name('admin.dashboard');

    Route::get('/landing', function () {
        return redirect()->route('dashboard');
    })->name('page.selector');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::get('/smart-finance', [FinanceController::class, 'index'])->name('finance.index');
    Route::post('/smart-finance', [FinanceController::class, 'analyze'])->name('finance.analyze');
    Route::get('/stata', [PageController::class, 'stata'])->name('stata');
    Route::get('/perpajakan', [TaxController::class, 'index'])->name('perpajakan.index');
    Route::post('/perpajakan', [TaxController::class, 'calculate'])->name('perpajakan.calculate');
});
