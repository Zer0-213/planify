<?php

namespace Tests\Services;

use App\Models\CompanyInvite;
use App\Models\CompanyUser;
use App\Models\User;
use App\Services\StaffService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class StaffServiceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function invitesNewStaffMemberSuccessfully(): void
    {
        $companyUser = CompanyUser::factory()->create();
        $data = [
            'email' => 'newstaff@example.com',
            'name' => 'New Staff',
            'phoneNumber' => '1234567890',
        ];

        $this->assertDatabaseMissing('company_invites', ['email' => $data['email']]);

        app(StaffService::class)->inviteStaffMember($companyUser, $data);

        $this->assertDatabaseHas('company_invites', [
            'email' => $data['email'],
            'name' => $data['name'],
            'phone_number' => $data['phoneNumber'],
            'company_id' => $companyUser->company_id,
        ]);
    }

    #[Test]
    public function throwsValidationExceptionIfEmailAlreadyRegistered(): void
    {
        $companyUser = CompanyUser::factory()->create();
        $existingUser = User::factory()->create(['email' => 'existing@example.com']);
        $data = ['email' => $existingUser->email, 'name' => 'Existing Staff'];

        $this->expectException(ValidationException::class);

        app(StaffService::class)->inviteStaffMember($companyUser, $data);
    }

    public function throwsValidationExceptionIfEmailAlreadyInvited(): void
    {
        $companyUser = CompanyUser::factory()->create();
        CompanyInvite::factory()->create(['email' => 'invited@example.com']);
        $data = ['email' => 'invited@example.com', 'name' => 'Invited Staff'];

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('This email is already registered or invited');

        app(StaffService::class)->inviteStaffMember($companyUser, $data);
    }

    #[Test]
    public function createsInviteWithDefaultPhoneNumberIfNotProvided(): void
    {
        $companyUser = CompanyUser::factory()->create();
        $data = [
            'email' => 'staff@example.com',
            'name' => 'Staff Member',
        ];

        app(StaffService::class)->inviteStaffMember($companyUser, $data);

        $this->assertDatabaseHas('company_invites', [
            'email' => $data['email'],
            'name' => $data['name'],
            'phone_number' => null,
        ]);
    }

    public function setsCorrectExpirationDateForInvite(): void
    {
        $companyUser = CompanyUser::factory()->create();
        $data = [
            'email' => 'expiry@example.com',
            'name' => 'Expiry Test',
        ];

        app(StaffService::class)->inviteStaffMember($companyUser, $data);

        $invite = CompanyInvite::where('email', $data['email'])->first();
        $this->assertNotNull($invite);
        $this->assertEquals(now()->addDays()->format('Y-m-d'), $invite->expires_at->format('Y-m-d'));
    }

    public function storesTokenForInvite(): void
    {
        $companyUser = CompanyUser::factory()->create();
        $data = [
            'email' => 'token@example.com',
            'name' => 'Token Test',
        ];

        app(StaffService::class)->inviteStaffMember($companyUser, $data);

        $invite = CompanyInvite::where('email', $data['email'])->first();
        $this->assertNotNull($invite);
        $this->assertNotEmpty($invite->token);
        $this->assertIsString($invite->token);
    }

    public function setsCorrectInviterInformation(): void
    {
        $companyUser = CompanyUser::factory()->create();
        $data = [
            'email' => 'inviter@example.com',
            'name' => 'Inviter Test',
        ];

        app(StaffService::class)->inviteStaffMember($companyUser, $data);

        $this->assertDatabaseHas('company_invites', [
            'email' => $data['email'],
            'invited_by' => $companyUser->user_id,
        ]);
    }
}
