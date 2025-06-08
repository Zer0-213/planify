<?php

namespace App\Services;

use App\Models\Company;

class StaffService
{
    public function __construct()
    {
    }

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
        $staff = $company->companyUsers()->with(['user', 'roles'])->get();

        foreach ($staff as $member) {
            $staffMembers[] = [
                'id' => $member->user->id,
                'name' => $member->user->name,
                'email' => $member->user->email,
                'phoneNumber' => $member->user->phone_number,
                'wage' => $member->wage ? $member->wage / 100 : 0,
                'role' => $member->roles->pluck('name')->first() ?? 'Staff',
            ];
        }

        return $staffMembers;
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
