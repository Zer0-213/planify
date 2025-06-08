<?php

namespace Tests\Controllers;

use App\Models\CompanyInvite;
use App\Models\CompanyUser;
use App\Services\StaffService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AcceptInviteTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function testPermissionsAreAttachedAfterAcceptingInvite(): void
    {
        $roleFactory = Role::create(['name' => 'admin']);
        $invite = CompanyInvite::factory()->create([
            'email' => 'test@example.com',
            'token' => 'valid-token',
            'expires_at' => now()->addDay(),
            'role_id' => $roleFactory->id,
        ]);


        $companyUser = CompanyUser::factory()->create([
            'company_id' => $invite->company_id,
            'user_id' => 1,
        ]);

        $staffService = Mockery::mock(StaffService::class);
        $staffService->shouldReceive('checkAndHandleInvitedUser')
            ->once()
            ->with($invite->id, 'valid-token')
            ->andReturn($companyUser);

        $this->app->instance(StaffService::class, $staffService);


        $this->actingAs($companyUser->user()->first());

        $response = $this->get(route('acceptInvite', [
            'invite_id' => $invite->id,
            'token' => 'valid-token',
        ]));

        $response->assertRedirect(route('dashboard'));
        $this->assertTrue(Session::has('just_accepted_invite'));
        $this->assertEquals($invite->company_id, Session::get('company_id'));
    }
}
