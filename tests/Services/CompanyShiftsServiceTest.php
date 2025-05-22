<?php

namespace Tests\Services;

use App\Models\Company;
use App\Models\Shift;
use App\Models\User;
use App\Services\CompanyShiftsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CompanyShiftsServiceTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var CompanyShiftsService
     */
    private CompanyShiftsService $service;

    #[Test]
    public function it_upserts_shifts_for_a_given_company()
    {
        // Arrange
        $user = User::factory()->create();
        $company = Company::factory()->create(['owner_id' => $user->id]);
        $companyUser = $company->companyUsers()->create(['user_id' => $user->id]);

        $shifts = [
            [
                'user_id' => $user->id,
                'shifts' => [
                    'monday' => [
                        'id' => null,
                        'date' => '2023-10-23',
                        'starts_at' => '2023-10-23 08:00:00',
                        'ends_at' => '2023-10-23 16:00:00',
                    ],
                    'tuesday' => [
                        'id' => null,
                        'date' => '2023-10-24',
                        'starts_at' => '2023-10-24 09:00:00',
                        'ends_at' => '2023-10-24 17:00:00',
                    ],
                ],
            ],
        ];

        $this->service->upsertShifts($shifts, new Shift(), $company);

        // Assert
        $this->assertDatabaseHas('shifts', [
            'company_user_id' => $companyUser->id,
            'starts_at' => '2023-10-23 08:00:00',
            'ends_at' => '2023-10-23 16:00:00',
        ]);

        $this->assertDatabaseHas('shifts', [
            'company_user_id' => $companyUser->id,
            'starts_at' => '2023-10-24 09:00:00',
            'ends_at' => '2023-10-24 17:00:00',
        ]);
    }

    #[Test]
    public function it_upserts_existing_shifts_with_an_id()
    {
        // Arrange
        $user = User::factory()->create();
        $company = Company::factory()->create(['owner_id' => $user->id]);
        $companyUser = $company->companyUsers()->create(['user_id' => $user->id]);

        // Create an initial shift
        (new Shift)->create([
            'id' => 100,
            'company_user_id' => $companyUser->id,
            'starts_at' => '2023-10-25 08:00:00',
            'ends_at' => '2023-10-25 16:00:00',
        ]);

        $shifts = [
            [
                'user_id' => $user->id,
                'shifts' => [
                    'wednesday' => [
                        'id' => 100,
                        'starts_at' => '2023-10-25 10:00:00',
                        'ends_at' => '2023-10-25 18:00:00',
                    ],
                ],
            ],
        ];

        // Act
        $this->service->upsertShifts($shifts, new Shift(), $company);

        // Assert
        $this->assertDatabaseHas('shifts', [
            'id' => 100,
            'company_user_id' => $companyUser->id,
            'starts_at' => '2023-10-25 10:00:00',
            'ends_at' => '2023-10-25 18:00:00',
        ]);
        
        // Make sure the old values were updated, not duplicated
        $this->assertDatabaseMissing('shifts', [
            'company_user_id' => $companyUser->id,
            'starts_at' => '2023-10-25 08:00:00',
            'ends_at' => '2023-10-25 16:00:00',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CompanyShiftsService();
    }
}
