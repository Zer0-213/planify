<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Permission;

class StaffService
{
    /**
     * Get the staff members for the company.
     *
     * @param Company $company
     * @return array
     */
    public function getStaffMembers(Company $company): array
    {
        $staffMembers = [];

        // Logic to retrieve staff members from the database or any other source
        $staff = $company->companyUsers()->with(['user', 'permissions'])->get();

        foreach ($staff as $member) {

            $staffMembers[] = [
                'id' => $member->user->id,
                'name' => $member->user->name,
                'email' => $member->user->email,
                'phoneNumber' => $member->user->phone_number,
                'wage' => $member->user->wage,
                'permissions' => $member->permissions->map(function (Permission $permission) {
                    return [
                        'name' => $permission->name,
                        'label' => $permission->label,
                    ];
                })->toArray(),
            ];
        }

        return $staffMembers;
    }

    /**
     * Add a new staff member.
     *
     * @param array $data
     * @return bool
     */
    public function addStaffMember(array $data): bool
    {
        // Logic to add a new staff member to the database
        return true;
    }

    /**
     * Update an existing staff member.
     *
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function updateStaffMember(int $id, array $data): bool
    {
        // Logic to update an existing staff member in the database
        return true;
    }

    /**
     * Delete a staff member.
     *
     * @param int $id
     * @return bool
     */
    public function deleteStaffMember(int $id): bool
    {
        // Logic to delete a staff member from the database
        return true;
    }

}
