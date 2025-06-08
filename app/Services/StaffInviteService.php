<?php

namespace App\Services;

use App\Jobs\SendStaffInviteEmailJob;
use App\Models\CompanyInvite;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Str;

readonly class StaffInviteService
{
    public function __construct(private User $user, private CompanyInvite $companyInvite)
    {
    }

    public function createStaffFromInvite(array $attributes, Role $role): CompanyUser
    {
        $invitedUser = $this->validateInvitation($attributes['invite_id'], $attributes['invite_token']);

        $user = $this->user->create([
            'name' => $invitedUser->name,
            'email' => $invitedUser->email,
            'password' => Hash::make($attributes['password']),
            'phone_number' => $invitedUser->phone_number ?? null,
        ]);

        $companyUser = $user->companyUsers()->create([
            'company_id' => $invitedUser->company_id,
            'wage' => $invitedUser->wage,
            'user_id' => $user->id,
        ]);


        $companyUser->roles()->attach($invitedUser->role_id);

        $roleWithPermissions = $role->with('permissions')->find($invitedUser->role_id);

        if ($roleWithPermissions && $roleWithPermissions->permissions->isNotEmpty()) {
            $companyUser->permissions()->sync(
                $roleWithPermissions->permissions->pluck('id')
            );
        }


        return $companyUser;
    }

    /**
     * Validate invitation data without processing it.
     *
     * @param int $inviteId
     * @param string $token
     * @return CompanyInvite
     * @throws UnauthorizedException
     */
    public function validateInvitation(int $inviteId, string $token): CompanyInvite
    {
        $invite = $this->companyInvite->query()->where('id', $inviteId)->first();

        if (!$invite) {
            throw new UnauthorizedException('Invitation not found.');
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
     * Invite a new staff member.
     *
     * @param CompanyUser $companyUser
     * @param array $data
     * @return void
     * @throws ValidationException
     */
    public function inviteStaffMember(CompanyUser $companyUser, array $data): void
    {
        $invited = $companyUser->whereHas('user', function ($query) use ($data) {
            $query->where('email', $data['email']);
        })
            ->where('company_id', $companyUser->company_id)
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
     * Check if the invited user is valid and handle the invitation acceptance.
     *
     * @param int $inviteId
     * @param string $token
     * @return ?CompanyUser
     * @throws UnauthorizedException
     */
    public function handleInvite(int $inviteId, string $token): ?CompanyUser
    {
        $invite = $this->validateInvitation($inviteId, $token);

        $user = $this->user->where('email', $invite->email)->first();

        if (!$user) {
            return null;
        }

        $companyUser = $user->companyUsers()->create([
            'company_id' => $invite->company_id,
            'wage' => $invite->wage,
            'user_id' => $user->id,
        ]);

        $companyUser->roles()->attach($invite->role_id);

        // Clean up the invite after successful acceptance
        $invite->delete();

        return $companyUser;
    }
}
