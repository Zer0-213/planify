<?php

namespace Services\StaffInviteService;

use App\Models\CompanyInvite;
use App\Models\CompanyUser;
use App\Models\User;
use App\Services\StaffService;
use Hash;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\UnauthorizedException;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Str;
use Tests\TestCase;


class AcceptInviteTest extends TestCase
{
    use RefreshDatabase;

    protected StaffService $service;
    private string $token;
    private User $user;

    /**
     * @throws BindingResolutionException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->token = Str::random(32);
        $this->service = app()->make(StaffService::class);
        $this->user = User::factory()->create(['email' => 'test@example.com']);
    }

    #[Test]
    public function validInviteReturnsCompanyUser(): void
    {
        $roleFactory = Role::findOrCreate('Admin', 'web');

        $invite = CompanyInvite::factory()->create([
            'email' => $this->user->email,
            'role_id' => $roleFactory->id,
            'token' => Hash::make($this->token),
        ]);


        $companyUser = $this->service->checkAndHandleInvitedUser($invite->id, $this->token);

        $this->assertInstanceOf(CompanyUser::class, $companyUser);
        $this->assertEquals($invite->company_id, $companyUser->company_id);
        $this->assertEquals($this->user->id, $companyUser->user_id);
    }

    #[Test]
    public function expiredInviteThrowsException(): void
    {

        $invite = CompanyInvite::factory()->create([
            'email' => $this->user->email,
            'token' => Hash::make($this->token),
            'expires_at' => now()->subDay(),
        ]);

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('This invite has expired.');

        $this->service->checkAndHandleInvitedUser($invite->id, $this->token);
    }

    public function invalidTokenThrowsException(): void
    {
        $invite = CompanyInvite::factory()->create([
            'email' => 'test@example.com',
            'token' => Hash::make('valid-token'),
            'expires_at' => now()->addDay(),
        ]);

        $this->expectException(UnauthorizedException::class);
        $this->expectExceptionMessage('Invalid invite token.');

        $this->service->checkAndHandleInvitedUser($invite->id, 'invalid-token');
    }

    public function missingInviteThrowsException(): void
    {
        $this->expectException(UnauthorizedException::class);

        $this->service->checkAndHandleInvitedUser(999, 'valid-token');
    }

    public function missingUserReturnsNull(): void
    {
        $invite = CompanyInvite::factory()->create([
            'email' => 'nonexistent@example.com',
            'token' => Hash::make('valid-token'),
            'expires_at' => now()->addDay(),
        ]);

        $this->mock(User::class, function ($mock) {
            $mock->shouldReceive('where')->with(['email' => 'nonexistent@example.com'])->andReturn(null);
        });

        $result = $this->service->checkAndHandleInvitedUser($invite->id, 'valid-token');

        $this->assertNull($result);
    }

}
