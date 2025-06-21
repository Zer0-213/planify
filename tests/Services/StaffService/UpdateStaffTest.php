<?php

namespace Tests\Services\StaffService;

use App\Enums\PermissionEnum;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use App\Services\StaffService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UpdateStaffTest extends TestCase
{
    use RefreshDatabase;

    private StaffService $staffService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->staffService = new StaffService(new CompanyUser(), new Role());
    }

    public function testUpdateStaffMemberReturnsFalseIfStaffMemberNotFound(): void
    {
        $this->expectException(ValidationException::class);
        $this->staffService->updateStaffMember(999, ['wage' => 20]);
    }

    public function testUpdateStaffMemberUpdatesWageSuccessfully(): void
    {
        $companyUser = CompanyUser::factory()->create(['wage' => 1500]);

        $this->staffService->updateStaffMember($companyUser->id, ['wage' => 20]);
        
        $companyUser->refresh();
        $companyUser->refresh();
        $this->assertDatabaseHas('company_users', [
            'id' => $companyUser->id,
            'wage' => 2000
        ]);
    }

    public function testUpdateStaffMemberAssignsRoleSuccessfully(): void
    {
        $companyUser = CompanyUser::factory()->create();

        $adminRole = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        $updatePermission = Permission::create(['name' => PermissionEnum::UPDATE_STAFF_MEMBER->value, 'guard_name' => 'web']);
        $deletePermission = Permission::create(['name' => PermissionEnum::DELETE_STAFF_MEMBER->value, 'guard_name' => 'web']);


        $adminRole->givePermissionTo([
            $updatePermission,
            $deletePermission,
        ]);
        $companyUser->assignRole($adminRole);

        $expectedRole = Role::create(['name' => 'Manager', 'guard_name' => 'web']);

        $expectedRole->givePermissionTo($updatePermission);

        $this->staffService->updateStaffMember($companyUser->id, ['role_id' => $expectedRole->id]);


        $companyUser->refresh();
        $this->assertTrue($companyUser->roles->contains('id', $expectedRole->id));
        $this->assertTrue($companyUser->hasPermission(PermissionEnum::UPDATE_STAFF_MEMBER));

        $this->assertFalse($companyUser->roles->contains('id', $adminRole->id));
        $this->assertFalse($companyUser->hasPermission(PermissionEnum::DELETE_STAFF_MEMBER));

    }

    public function testUpdateStaffMemberUpdatesWageAndAssignsRole(): void
    {
        $companyUser = CompanyUser::factory()->create(['wage' => 1500]);

        $role = Role::create(['name' => 'Supervisor', 'guard_name' => 'web']);

        $this->staffService->updateStaffMember($companyUser->id, [
            'wage' => 30,
            'role_id' => $role->id
        ]);

        $companyUser->refresh();
        $this->assertEquals(3000, $companyUser->wage);
        $this->assertTrue($companyUser->hasRole('Supervisor'));
    }

    public function testUpdateStaffMemberWithInvalidRoleId(): void
    {
        $companyUser = CompanyUser::factory()->create();

        $this->expectException(ValidationException::class);
        $this->expectExceptionMessage('Role invalid');
        $this->staffService->updateStaffMember($companyUser->id, ['role_id' => 999]);

    }

    public function testUpdateStaffMemberWithNoDataToUpdate(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $companyUser = CompanyUser::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
        ]);

        $this->staffService->updateStaffMember($companyUser->id, []);

        $companyUser->refresh();

        $this->assertTrue($companyUser->is($companyUser));
    }
}
