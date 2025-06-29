<?php

namespace Services\StaffService;

use App\Models\CompanyUser;
use App\Models\Shift;
use App\Services\StaffService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;


class DeleteStaffTest extends TestCase
{
    use RefreshDatabase;

    private StaffService $staffService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->staffService = new StaffService(new CompanyUser(), app(Role::class));
    }

    #[Test]
    public function testDeleteStaffMemberDeletesRecord(): void
    {
        $companyUser = CompanyUser::factory()->create();

        $this->staffService->deleteStaffMember($companyUser->id);

        $this->assertDatabaseMissing('company_users', [
            'id' => $companyUser->id,
        ]);
    }

    #[Test]
    public function testDeleteStaffMemberDoesNothingIfNotFound(): void
    {
        $this->staffService->deleteStaffMember(999);

        $this->assertTrue(true);
    }

    #[Test]
    public function testDeleteStaffMemberWithShifts(): void
    {
        $companyUser = CompanyUser::factory()->create();
        Shift::factory()->create(['company_user_id' => $companyUser->id]);

        $this->staffService->deleteStaffMember($companyUser->id);

        $this->assertDatabaseMissing('company_users', [
            'id' => $companyUser->id,
        ]);

        $this->assertDatabaseMissing('shifts', [
            'company_user_id' => $companyUser->id,
        ]);
    }
}
