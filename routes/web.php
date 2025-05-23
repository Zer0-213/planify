<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShiftsController;
use App\Http\Middleware\UserHasCompany;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('company/create', [CompanyController::class, 'create'])->name('company.create');
    Route::post('company/store', [CompanyController::class, 'store'])->name('company.store');
});

Route::middleware(['auth', 'verified', UserHasCompany::class])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('shifts', [ShiftsController::class, 'index'])->name('shifts.index');
    Route::post('shifts', [ShiftsController::class, 'store'])->name('shifts.store');
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
