<?php

namespace App\Services;

use App\Jobs\SendStaffInviteEmailJob;
use App\Models\Company;
use App\Models\CompanyInvite;
use App\Models\CompanyUser;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Str;

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
                'wage' => $member->wage ? $member->wage / 100 : 0,
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
     * Invite a new staff member.
     *
     * @param CompanyUser $companyUser
     * @param array $data
     * @return void
     * @throws ValidationException
     */
    public function inviteStaffMember(CompanyUser $companyUser, array $data): void
    {

        if (User::where('email', $data['email'])->exists() ||
            CompanyInvite::query()->where('email', $data['email'])->exists()) {
            throw ValidationException::withMessages(['email' => 'This email is already in use or pending invitation']);
        }


        $token = Str::uuid();
        $invite = CompanyInvite::query()->create([
            'company_id' => $companyUser->company_id,
            'email' => $data['email'],
            'name' => $data['name'],
            'phone_number' => $data['phoneNumber'] ?? null,
            'token' => Hash::make($token),
            'invited_by' => $companyUser->user_id,
            'expires_at' => now()->addDays(),
        ]);


        SendStaffInviteEmailJob::dispatch($invite, $token);

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
