<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\FinancialTargetController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\StataController;
use App\Http\Controllers\TaxController;
use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/beranda');
Route::get('/beranda', [PageController::class, 'landing'])->name('home');
Route::get('/informasi-perpajakan', [PageController::class, 'taxInformation'])
    ->name('perpajakan.info');
Route::get('/informasi-stata', [PageController::class, 'stataInformation'])
    ->name('stata.info');

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
    })->middleware('role:user,admin')->name('dashboard.user');

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

    Route::patch('/dashboard/admin/users/{user}/role', [AdminUserController::class, 'updateRole'])
        ->middleware('role:admin')
        ->name('admin.users.role');

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
    Route::get('/smart-finance', [FinanceController::class, 'index'])
        ->middleware('activity:page_open,smart_finance,Smart Finance')
        ->name('finance.index');
    Route::post('/smart-finance', [FinanceController::class, 'analyze'])->name('finance.analyze');
    Route::delete('/smart-finance/{id}', [FinanceController::class, 'destroy'])->name('finance.destroy');
    Route::get('/smart-finance/{id}/pdf', [FinanceController::class, 'exportPdf'])->name('finance.export-pdf');
    Route::get('/smart-finance/templates', [FinanceController::class, 'getTemplates'])->name('finance.templates');
    Route::post('/smart-finance/apply-template', [FinanceController::class, 'applyTemplate'])->name('finance.apply-template');
    
    // Financial Targets
    Route::get('/targets', [FinancialTargetController::class, 'index'])->name('targets.index');
    Route::get('/targets/create', [FinancialTargetController::class, 'create'])->name('targets.create');
    Route::post('/targets', [FinancialTargetController::class, 'store'])->name('targets.store');
    Route::get('/targets/{target}', [FinancialTargetController::class, 'show'])->name('targets.show');
    Route::get('/targets/{target}/edit', [FinancialTargetController::class, 'edit'])->name('targets.edit');
    Route::put('/targets/{target}', [FinancialTargetController::class, 'update'])->name('targets.update');
    Route::delete('/targets/{target}', [FinancialTargetController::class, 'destroy'])->name('targets.destroy');
    Route::post('/targets/{target}/deposit', [FinancialTargetController::class, 'addDeposit'])->name('targets.add-deposit');
    Route::delete('/targets/deposit/{deposit}', [FinancialTargetController::class, 'removeDeposit'])->name('targets.remove-deposit');
    
    Route::get('/stata', [StataController::class, 'index'])
        ->middleware('activity:page_open,stata,Stata')
        ->name('stata');
    Route::post('/stata/import', [StataController::class, 'import'])->name('stata.import');
    Route::post('/stata/command', [StataController::class, 'run'])->name('stata.command');
    Route::delete('/stata/dataset', [StataController::class, 'clear'])->name('stata.clear');
    Route::get('/perpajakan', [TaxController::class, 'index'])
        ->middleware('activity:page_open,perpajakan,Perpajakan')
        ->name('perpajakan.index');
    Route::post('/perpajakan', [TaxController::class, 'calculate'])->name('perpajakan.calculate');
    Route::delete('/perpajakan/{id}', [TaxController::class, 'destroy'])->name('perpajakan.destroy');
    Route::get('/perpajakan/{id}/pdf', [TaxController::class, 'exportPdf'])->name('perpajakan.export-pdf');
});
