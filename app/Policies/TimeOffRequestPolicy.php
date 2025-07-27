<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\CompanyUser;
use App\Models\TimeOffRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class TimeOffRequestPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any time off requests.
     */
    public function viewAny(CompanyUser $companyUser): bool
    {
        // Allow users to view their own requests
        // Admins/managers can view all with appropriate permission
        return $companyUser->hasPermission(PermissionEnum::VIEW_TIME_OFF_REQUESTS);
    }

    /**
     * Determine whether the user can view the time off request.
     */
    public function view(CompanyUser $companyUser, TimeOffRequest $timeOffRequest): bool
    {
        // Users can view their own time off requests
        if ($timeOffRequest->company_user_id === $companyUser->id) {
            return true;
        }

        // Admins/managers can view all time off requests
        return $companyUser->hasPermission(PermissionEnum::VIEW_TIME_OFF_REQUESTS);
    }

    /**
     * Determine whether the user can create time off requests.
     */
    public function create(CompanyUser $companyUser): bool
    {
        // Everyone should be able to request time off
        return $companyUser->hasPermission(PermissionEnum::MANAGE_TIME_OFF_REQUESTS);
    }

    /**
     * Determine whether the user can update the time off request.
     */
    public function update(CompanyUser $companyUser, TimeOffRequest $timeOffRequest): bool
    {
        // Users can update their own pending time off requests
        if ($timeOffRequest->company_user_id === $companyUser->id && $timeOffRequest->isPending()) {
            return true;
        }

        // Admins/managers can update any time off request
        return $companyUser->hasPermission(PermissionEnum::MANAGE_TIME_OFF_REQUESTS);
    }

    /**
     * Determine whether the user can delete the time off request.
     */
    public function delete(CompanyUser $companyUser, TimeOffRequest $timeOffRequest): bool
    {
        // Users can delete their own pending time off requests
        if ($timeOffRequest->company_user_id === $companyUser->id && $timeOffRequest->isPending()) {
            return true;
        }

        // Admins/managers can delete any time off request
        return $companyUser->hasPermission(PermissionEnum::REQUEST_TIME_OFF);
    }

    /**
     * Determine whether the user can approve or reject time off requests.
     */
    public function respond(CompanyUser $companyUser, TimeOffRequest $timeOffRequest): bool
    {
        // Only admins/managers can approve/reject time off requests
        // Users can't approve their own requests
        if ($timeOffRequest->company_user_id === $companyUser->id) {
            return false;
        }

        return $companyUser->hasPermission(PermissionEnum::MANAGE_TIME_OFF_REQUESTS);
    }

    /**
     * Determine whether the user can restore the time off request.
     */
    public function restore(CompanyUser $companyUser): bool
    {
        // Only admins/managers can restore deleted time off requests
        return $companyUser->hasPermission(PermissionEnum::MANAGE_TIME_OFF_REQUESTS);
    }

    /**
     * Determine whether the user can permanently delete the time off request.
     */
    public function forceDelete(CompanyUser $companyUser): bool
    {
        // Only admins/managers can force delete time off requests
        return $companyUser->hasPermission(PermissionEnum::MANAGE_TIME_OFF_REQUESTS);
    }
}
