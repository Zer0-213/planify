<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShiftsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffInviteController;
use App\Http\Middleware\UserHasCompany;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', static function () {
    return Inertia::render('Welcome');
})->name('home');

Route::controller(StaffInviteController::class)->group(function () {
    Route::get('/accept-invite', 'acceptInvite')->name('acceptInvite');
    Route::get('/register-invite', 'showInvitedUserForm')->name('showInvitedUserForm');
    Route::post('staff/invite', 'inviteStaff')->middleware(['auth', 'verified', UserHasCompany::class])->name('staff.invite');
    Route::post('staff/create-from-invite', 'createUserFromInvite')->name('staff.createUserFromInvite');
});


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('company/create', [CompanyController::class, 'create'])->name('company.create');
    Route::post('company/store', [CompanyController::class, 'store'])->name('company.store');
});

Route::middleware(['auth', 'verified', UserHasCompany::class])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(ShiftsController::class)->prefix('shifts')->group(function () {
        Route::get('/', 'index')->name('shifts.index');
        Route::post('/', 'store')->name('shifts.store');
    });

    Route::controller(StaffController::class)->prefix('staff')->group(function () {
        Route::get('/', 'index')->name('staff.index');
        Route::delete('/destroy/{id}', 'deleteStaffMember')->name('staff.destroy');
        Route::put('/update/{id}', 'updateStaffMember')->name('staff.update');
    });
});


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
