<?php

namespace Services\StaffInviteService;

use App\Models\CompanyInvite;
use App\Models\CompanyUser;
use App\Models\User;
use App\Services\StaffService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class InviteUserTest extends TestCase
{
    use RefreshDatabase;


    /**
     * @throws ValidationException
     */
    #[Test]
    public function invitesNewStaffMemberSuccessfully(): void
    {
        $companyUser = CompanyUser::factory()->create();
        $data = [
            'email' => 'newstaff@example.com',
            'name' => 'New Staff',
            'phoneNumber' => '1234567890',
            'role' => 'Staff',
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

    #[Test]
    public function throwsValidationExceptionIfEmailAlreadyInvited(): void
    {
        $companyUser = CompanyUser::factory()->create();
        CompanyInvite::factory()->create(['email' => 'invited@example.com']);
        $data = ['email' => 'invited@example.com', 'name' => 'Invited Staff'];

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('This email is already in use or pending invitation');


        app(StaffService::class)->inviteStaffMember($companyUser, $data);
    }

    #[Test]
    public function createsInviteWithDefaultPhoneNumberIfNotProvided(): void
    {
        // Set up queue fake to prevent actual job dispatch
        Queue::fake();

        $companyUser = CompanyUser::factory()->create();
        $data = [
            'email' => 'staff@example.com',
            'name' => 'Staff Member',
        ];

        $staffService = new StaffService();


        $staffService->inviteStaffMember($companyUser, $data);

        $this->assertDatabaseHas('company_invites', [
            'email' => $data['email'],
            'name' => $data['name'],
            'phone_number' => null,
        ]);
    }

    /**
     * @throws ValidationException
     */
    #[Test]
    public function setsCorrectExpirationDateForInvite(): void
    {

        $companyUser = CompanyUser::factory()->create();
        $data = [
            'email' => 'expiry@example.com',
            'name' => 'Expiry Test',
        ];

        $staffService = new StaffService();
        $staffService->inviteStaffMember($companyUser, $data);

        $invite = CompanyInvite::where('email', $data['email'])->first();
        $this->assertNotNull($invite);
        $this->assertEquals(now()->addDays()->format('Y-m-d'), $invite->expires_at->format('Y-m-d'));
    }

    #[Test]
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

    #[Test]
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


    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }
}
