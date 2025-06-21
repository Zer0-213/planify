<?php

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\CompanyUser;
use App\Models\User;

class StaffPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CompanyUser $companyUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(CompanyUser $companyUser): bool
    {
        return $companyUser->hasPermission(PermissionEnum::UPDATE_STAFF_MEMBER);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(CompanyUser $companyUser): bool
    {
        return $companyUser->hasPermission(PermissionEnum::DELETE_STAFF_MEMBER);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CompanyUser $companyUser): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CompanyUser $companyUser): bool
    {
        return false;
    }
}
