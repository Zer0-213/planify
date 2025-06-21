<?php

namespace App\Services;

use App\Models\Company;
use App\Models\CompanyUser;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;

readonly class StaffService
{

    public function __construct(private CompanyUser $companyUser, private Role $role)
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
                'id' => $member->id,
                'userId' => $member->user->id,
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
        $companyUser = $this->companyUser
            ->with(['roles', 'company'])
            ->find($id);

        if (!$companyUser) {
            return false;
        }

        if (isset($data['wage']) && $companyUser->wage !== $data['wage'] * 100) {
            $companyUser->wage = $data['wage'] * 100;
        }

        if (isset($data['role_id'])) {
            $newRoleId = $data['role_id'];

            $currentRoleIds = $companyUser->roles->pluck('id')->toArray();

            if (!in_array($newRoleId, $currentRoleIds)) {
                if ($companyUser->user_id === $companyUser->company->owner_id) {
                    throw ValidationException::withMessages(['error' => 'You cannot change the role of the company owner.']);
                }

                $role = $this->role
                    ->newQuery()
                    ->with('permissions')
                    ->find($newRoleId);

                if (!$role) {
                    throw ValidationException::withMessages(['error' => 'Role not found.']);
                }

                $companyUser->roles()->sync([$role->id]);
                $companyUser->permissions()->sync($role->permissions->pluck('id')->toArray());
            }
        }

        return $companyUser->save();
    }


    /**
     * Delete a staff member.
     *
     * @param int $staffId
     * @return void
     */
    public function deleteStaffMember(int $staffId): void
    {
        $this->companyUser->find($staffId)?->delete();
    }
}
