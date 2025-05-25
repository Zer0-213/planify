<?php

namespace Tests\Services;

use App\Models\Company;
use App\Models\Shift;
use App\Models\User;
use App\Services\CompanyShiftsService;
use Carbon\Carbon;
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
        (new Shift)->forceFill([
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


        $this->assertDatabaseMissing('shifts', [
            'company_user_id' => $companyUser->id,
            'starts_at' => '2023-10-25 08:00:00',
            'ends_at' => '2023-10-25 16:00:00',
        ]);
    }

    #[Test]
    public function it_correctly_upserts_multiple_shifts_and_keeps_correct_count()
    {
        // Arrange
        $user = User::factory()->create();
        $company = Company::factory()->create(['owner_id' => $user->id]);
        $companyUser = $company->companyUsers()->create(['user_id' => $user->id]);

        // Create initial shift that will be updated
        $shift = new Shift();
        $shift->forceFill([
            'id' => 100,
            'company_user_id' => $companyUser->id,
            'starts_at' => '2023-10-23 08:00:00',
            'ends_at' => '2023-10-23 16:00:00',
        ])->save();

        $this->assertEquals(1, (new Shift)->count());

        // Data for upserting - 1 update and 2 new shifts
        $shifts = [
            [
                'user_id' => $user->id,
                'shifts' => [
                    'monday' => [
                        'id' => 100, // This will update existing shift
                        'starts_at' => '2023-10-23 10:00:00', // Changed time
                        'ends_at' => '2023-10-23 18:00:00',   // Changed time
                    ],
                    'wednesday' => [
                        'id' => null, // New shift
                        'starts_at' => '2023-10-25 08:00:00',
                        'ends_at' => '2023-10-25 16:00:00',
                    ],
                    'thursday' => [
                        'id' => null, // New shift
                        'starts_at' => '2023-10-26 08:00:00',
                        'ends_at' => '2023-10-26 16:00:00',
                    ],
                ],
            ],
        ];

        // Act
        $this->service->upsertShifts($shifts, new Shift(), $company);

        // Assert
        // We should have 3 shifts total (1 updated + 2 new)
        $this->assertEquals(3, (new Shift)->count());

        // Verify the updated shift has new times
        $this->assertDatabaseHas('shifts', [
            'id' => 100,
            'company_user_id' => $companyUser->id,
            'starts_at' => '2023-10-23 10:00:00',
            'ends_at' => '2023-10-23 18:00:00',
        ]);
    }

    #[Test]
    public function it_returns_shifts_for_the_specified_week_with_correct_details()
    {
        // Arrange
        $user = User::factory()->create(['name' => 'John Doe']);
        $company = Company::factory()->create(['owner_id' => $user->id]);
        $companyUser = $company->companyUsers()->create(['user_id' => $user->id]);


        // Or create shifts
        $shifts = Shift::factory()->createShifts($companyUser, [
            ['starts_at' => '2023-10-23 08:00:00', 'ends_at' => '2023-10-23 16:00:00'],
            ['starts_at' => '2023-10-25 09:00:00', 'ends_at' => '2023-10-25 17:00:00'],
        ]);


        $startWeek = Carbon::parse('2023-10-23');
        $endWeek = Carbon::parse('2023-10-29');

        // Act
        $result = $this->service->getShiftsForWeek($company, $startWeek, $endWeek);

        // Assert
        $this->assertCount(1, $result);
        $this->assertEquals($user->id, $result[0]['user_id']);
        $this->assertEquals('John Doe', $result[0]['name']);
        $this->assertCount(2, $result[0]['shifts']);
        $this->assertEquals('2023-10-23T08:00:00+00:00', $result[0]['shifts'][0]['starts_at']);
        $this->assertEquals('2023-10-25T09:00:00+00:00', $result[0]['shifts'][1]['starts_at']);
    }

    #[Test]
    public function it_handles_empty_shifts_for_week()
    {
        // Arrange
        $user = User::factory()->create();
        $company = Company::factory()->create(['owner_id' => $user->id]);
        $company->companyUsers()->create(['user_id' => $user->id]);

        $startWeek = Carbon::parse('2023-10-23');
        $endWeek = Carbon::parse('2023-10-29');

        // Act
        $result = $this->service->getShiftsForWeek($company, $startWeek, $endWeek);

        // Assert
        $this->assertEmpty($result);
    }

    #[Test]
    public function it_filters_shifts_based_on_week_boundaries()
    {
        // Arrange
        $user = User::factory()->create();
        $company = Company::factory()->create(['owner_id' => $user->id]);
        $companyUser = $company->companyUsers()->create(['user_id' => $user->id]);

        Shift::factory()->create([
            'company_user_id' => $companyUser->id,
            'starts_at' => '2023-10-22 08:00:00', // Outside start of week
            'ends_at' => '2023-10-22 16:00:00',
        ]);

        Shift::factory()->create([
            'company_user_id' => $companyUser->id,
            'starts_at' => '2023-10-23 08:00:00', // Inside week
            'ends_at' => '2023-10-23 16:00:00',
        ]);

        Shift::factory()->create([
            'company_user_id' => $companyUser->id,
            'starts_at' => '2023-10-30 08:00:00', // Outside end of week
            'ends_at' => '2023-10-30 16:00:00',
        ]);

        $startWeek = Carbon::parse('2023-10-23');
        $endWeek = Carbon::parse('2023-10-29');

        // Act
        $result = $this->service->getShiftsForWeek($company, $startWeek, $endWeek);

        // Assert
        $this->assertCount(1, $result[0]['shifts']);
        $this->assertEquals('2023-10-23T08:00:00+00:00', $result[0]['shifts'][0]['starts_at']);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new CompanyShiftsService();
    }
}
