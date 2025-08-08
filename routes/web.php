<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShiftsController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffInviteController;
use App\Http\Controllers\TimeOffRequestController;
use App\Http\Middleware\UserHasCompany;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Welcome
Route::get('/', static function () {
    return Inertia::render('Welcome');
})->name('home');

// Invite staff member
Route::controller(StaffInviteController::class)->group(function () {
    Route::get('/accept-invite', 'acceptInvite')->name('acceptInvite');
    Route::get('/register-invite', 'showInvitedUserForm')->name('showInvitedUserForm');
    Route::post('staff/invite', 'inviteStaff')->middleware(['auth', 'verified', UserHasCompany::class])->name('staff.invite');
    Route::post('staff/create-from-invite', 'createUserFromInvite')->name('staff.createUserFromInvite');
});

// Company onboarding/creation (for users without companies)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(CompanyController::class)->prefix('company')->group(function () {
        Route::get('/', 'index')->name('company.index'); // Shows create form
        Route::post('/', 'store')->name('company.store');
        Route::get('/deleted-notice', 'showDeletedDialog')->name('company.deletedNotice');
    });
});

// Main application routes (for users with companies)
Route::middleware(['auth', 'verified', UserHasCompany::class])->group(function () {

    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Shifts
    Route::controller(ShiftsController::class)->prefix('shifts')->group(function () {
        Route::get('/', 'index')->name('shifts.index');
        Route::post('/', 'store')->name('shifts.store');
    });

    // Staff
    Route::controller(StaffController::class)->prefix('staff')->group(function () {
        Route::get('/', 'index')->name('staff.index');
        Route::delete('/destroy/{id}', 'deleteStaffMember')->name('staff.destroy');
        Route::put('/update/{id}', 'updateStaffMember')->name('staff.update');
    });

    // TimeOffRequests
    Route::controller(TimeOffRequestController::class)->prefix('time-off')->group(function () {
        Route::get('/', 'index')->name('time-off.index');
        Route::post('/request', 'requestTimeOff')->name('time-off-requests.store');
        Route::put('/update/{timeOffRequest}', 'updateTimeOff')->name('time-off-requests.update');
        Route::delete('/delete/{timeOffRequest}', 'deleteTimeOff')->name('time-off-requests.delete');
        Route::patch('/respond/{timeOffRequest}', 'respondToTimeOff')->name('time-off-requests.respond');
    });
});

require __DIR__ . '/auth.php';
require __DIR__ . '/settings.php';
