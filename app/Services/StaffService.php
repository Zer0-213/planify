<?php

namespace App\Services;

use App\Jobs\SendStaffInviteEmailJob;
use App\Models\Company;
use App\Models\CompanyInvite;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
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
     * Invite a new staff member.
     *
     * @param CompanyUser $companyUser
     * @param array $data
     * @return void
     * @throws ValidationException
     */
    public function inviteStaffMember(CompanyUser $companyUser, array $data): void
    {

        $invited = CompanyInvite::query()
            ->where(['email' => $data['email'], 'company_id' => $companyUser->company_id,])
            ->exists();

        if ($invited || $companyUser->company->users()->where('email', $data['email'])->exists()) {
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
            'wage' => isset($data['wage']) ? $data['wage'] * 100 : null,
            'role_id' => Role::findByName($data['role'])->id
        ]);


        SendStaffInviteEmailJob::dispatch($invite, $token);

    }

    /**
     * Check if the invited user is valid and return the invite.
     *
     * @param int $inviteId
     * @param string $token
     * @param CompanyInvite $companyInvite
     * @return CompanyInvite
     * @throws UnauthorizedException
     */
    public function checkAndGetInvitedUser(int $inviteId, string $token, CompanyInvite $companyInvite): CompanyInvite
    {
        $invite = $companyInvite->query()->where('id', $inviteId)->first();

        if (!$invite) {
            throw new UnauthorizedException();
        }

        if ($invite->hasExpired()) {
            throw new UnauthorizedException('This invite has expired.');
        }

        if (!Hash::check($token, $invite->token)) {
            throw new UnauthorizedException('Invalid invite token.');
        }


        return $invite;
    }

    /**
     * Add a new staff member.
     *
     * @param array $data
     * @param int $companyId
     * @return void
     * @throws ValidationException
     */
    public function addStaffMember(array $data, int $companyId): void
    {
        $user = null;

        if (User::where('email', $data['email'])->exists()) {
            $user = User::where('email', $data['email'])->first();
            if ($user->companies()->where('company_id', $companyId)->exists()) {
                throw new ValidationException(['email' => 'This user is already a member of this company']);
            }
        } else {
            $user = User::query()->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone_number' => $data['phoneNumber'] ?? null,
                'password' => Hash::make($data['password']),
            ]);
        }
        $newStaff = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone_number' => $data['phoneNumber'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        $newCompanyUser = CompanyUser::query()->create([
            'company_id' => $companyId,
            'user_id' => $newStaff->id,
            'wage' => isset($data['wage']) ? $data['wage'] * 100 : null,
        ]);

        if (isset($data['role'])) {
            $newCompanyUser->roles()->sync([$data['role']]);
        }
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
