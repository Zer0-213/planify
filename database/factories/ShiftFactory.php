<?php

namespace Database\Factories;

use App\Models\CompanyUser;
use App\Models\Shift;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Shift>
 */
class ShiftFactory extends Factory
{


    /**
     * Create shifts for specific dates with exact timestamps.
     *
     * @param CompanyUser $companyUser The company user to associate with the shifts
     * @param array $shifts Array of shifts with starts_at and ends_at datetime strings
     * @return array Array of created Shift models
     */
    public static function createShifts(CompanyUser $companyUser, array $shifts): array
    {
        $createdShifts = [];

        foreach ($shifts as $shift) {
            $createdShifts[] = Shift::factory()->create([
                'company_user_id' => $companyUser->id,
                'starts_at' => $shift['starts_at'],
                'ends_at' => $shift['ends_at'],
                'shift_date' => $shift['shift_date'],
            ]);
        }

        return $createdShifts;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = Carbon::now()->addDays(rand(-10, 10))->setHour(8 + rand(0, 4))->setMinute(0)->setSecond(0);
        $endDate = (clone $startDate)->addHours(rand(4, 8));

        return [
            'starts_at' => $startDate,
            'ends_at' => $endDate,
            'break_duration' => rand(0, 60), // Break duration in minutes
            'location' => $this->faker->optional()->address(),
            'notes' => $this->faker->optional()->sentence(),
            'status' => $this->faker->randomElement(["scheduled", "completed", "cancelled"]),
            'shift_date' => Carbon::now(),
        ];
    }


}
