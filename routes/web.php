<?php

use App\Http\Controllers\{BillingController,
    CompanyController,
    DashboardController,
    ShiftsController,
    StaffController,
    StaffInviteController,
    TimeOffRequestController};
use App\Http\Middleware\EnsureCompanyIsSubscribed;
use App\Http\Middleware\UserHasCompany;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// ----------
// Public Routes
// ----------

Route::get('/', static function () {
    return Inertia::render('Welcome');
})->name('home');

// Staff Invite Routes (some public, some protected)
Route::controller(StaffInviteController::class)->group(function () {
    Route::get('/accept-invite', 'acceptInvite')->name('acceptInvite');
    Route::get('/register-invite', 'showInvitedUserForm')->name('showInvitedUserForm');
    Route::post('staff/create-from-invite', 'createUserFromInvite')->name('staff.createUserFromInvite');
});

// ----------
// Authenticated Routes
// ----------
Route::middleware('auth')->group(function () {

    // Company onboarding (for users without a company)
    Route::prefix('company')->controller(CompanyController::class)->group(function () {
        Route::get('/', 'index')->name('company.index');
        Route::post('/', 'store')->name('company.store');
        Route::get('/deleted-notice', 'showDeletedDialog')->name('company.deletedNotice');
    });

    // Billing routes (used in both onboarding & main app)
    Route::prefix('billing')->controller(BillingController::class)->group(function () {
        Route::get('/notice', 'notice')->name('billing.notice');
        Route::get('/portal', 'portal')->name('billing.portal');
        Route::get('/processing', 'processing')->name('billing.processing');
        Route::get('/check-subscription', 'checkSubscription')->name('billing.checkSubscription');
    });


    // Routes for users who **belong to a company**
    Route::middleware(UserHasCompany::class)
        ->middleware(EnsureCompanyIsSubscribed::class)
        ->group(function () {

            // Staff invite (protected invite action)
            Route::post('staff/invite', [StaffInviteController::class, 'inviteStaff'])->name('staff.invite');

            // Dashboard
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

            // Shifts
            Route::prefix('shifts')->controller(ShiftsController::class)->group(function () {
                Route::get('/', 'index')->name('shifts.index');
                Route::post('/', 'store')->name('shifts.store');
            });

            // Staff
            Route::prefix('staff')->controller(StaffController::class)->group(function () {
                Route::get('/', 'index')->name('staff.index');
                Route::delete('/destroy/{id}', 'deleteStaffMember')->name('staff.destroy');
                Route::put('/update/{id}', 'updateStaffMember')->name('staff.update');
            });

            // Time Off Requests
            Route::prefix('time-off')->controller(TimeOffRequestController::class)->group(function () {
                Route::get('/', 'index')->name('time-off.index');
                Route::post('/request', 'requestTimeOff')->name('time-off-requests.store');
                Route::put('/update/{timeOffRequest}', 'updateTimeOff')->name('time-off-requests.update');
                Route::delete('/delete/{timeOffRequest}', 'deleteTimeOff')->name('time-off-requests.delete');
                Route::patch('/respond/{timeOffRequest}', 'respondToTimeOff')->name('time-off-requests.respond');
            });
        });
});

// Auth and settings
require __DIR__ . '/auth.php';
require __DIR__ . '/settings.php';
