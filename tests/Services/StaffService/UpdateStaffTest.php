<?php

namespace Tests\Services\StaffService;

use App\Enums\PermissionEnum;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use App\Services\StaffService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $this->staffService = new StaffService(new CompanyUser());
    }

    public function testUpdateStaffMemberReturnsFalseIfStaffMemberNotFound(): void
    {
        $result = $this->staffService->updateStaffMember(999, ['wage' => 20]);

        $this->assertFalse($result);
    }

    public function testUpdateStaffMemberUpdatesWageSuccessfully(): void
    {
        // Create test data
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $companyUser = CompanyUser::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'wage' => 1500, // Initial wage in cents
        ]);

        $result = $this->staffService->updateStaffMember($companyUser->id, ['wage' => 20]);

        $this->assertTrue($result);

        $companyUser->refresh();
        $this->assertEquals(2000, $companyUser->wage); // 20 * 100 = 2000 cents
    }

    public function testUpdateStaffMemberAssignsRoleSuccessfully(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $companyUser = CompanyUser::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
        ]);

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

        $result = $this->staffService->updateStaffMember($companyUser->id, ['role_id' => $expectedRole->id]);

        $this->assertTrue($result);

        $companyUser->refresh();
        $this->assertTrue($companyUser->roles->contains('id', $expectedRole->id));
        $this->assertTrue($companyUser->hasPermission(PermissionEnum::UPDATE_STAFF_MEMBER));

        $this->assertFalse($companyUser->roles->contains('id', $adminRole->id));
        $this->assertFalse($companyUser->hasPermission(PermissionEnum::DELETE_STAFF_MEMBER));

    }

    public function testUpdateStaffMemberUpdatesWageAndAssignsRole(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $companyUser = CompanyUser::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'wage' => 1500,
        ]);

        $role = Role::create(['name' => 'Supervisor', 'guard_name' => 'web']);

        $result = $this->staffService->updateStaffMember($companyUser->id, [
            'wage' => 30,
            'role_id' => $role->id
        ]);

        $this->assertTrue($result);

        $companyUser->refresh();
        $this->assertEquals(3000, $companyUser->wage);
        $this->assertTrue($companyUser->hasRole('Supervisor'));
    }

    public function testUpdateStaffMemberWithInvalidRoleId(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $companyUser = CompanyUser::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
        ]);

        // This should still return true even if role assignment fails
        // because the save() operation succeeds
        $result = $this->staffService->updateStaffMember($companyUser->id, ['role_id' => 999]);

        $this->assertTrue($result);
    }

    public function testUpdateStaffMemberWithNoDataToUpdate(): void
    {
        // Create test data
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $companyUser = CompanyUser::factory()->create([
            'user_id' => $user->id,
            'company_id' => $company->id,
        ]);

        $result = $this->staffService->updateStaffMember($companyUser->id, []);

        $this->assertTrue($result);
    }
}
