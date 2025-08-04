<?php

namespace Database\Factories;

use App\Models\CompanyUser;
use App\Models\TimeOffRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TimeOffRequest>
 */
class TimeOffRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('now', '+3 months');
        $endDate = $this->faker->dateTimeBetween($startDate, $startDate->format('Y-m-d') . ' +2 weeks');
        $isFullDay = $this->faker->boolean(70);

        return [
            'company_user_id' => CompanyUser::factory(),
            'start_date' => $startDate,
            'start_time' => $isFullDay ? null : $this->faker->time('H:i'),
            'end_date' => $endDate,
            'end_time' => $isFullDay ? null : $this->faker->time('H:i'),
            'is_full_day' => $isFullDay,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'reason' => $this->faker->optional(0.8)->sentence(),
            'admin_notes' => $this->faker->optional(0.3)->sentence(),
            'approved_by' => null,
            'approved_at' => null,
        ];
    }

    /**
     * Create a pending time off request
     */
    public function pending(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'pending',
            'approved_by' => null,
            'approved_at' => null,
            'admin_notes' => null,
        ]);
    }

    /**
     * Create an approved time off request
     */
    public function approved(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'approved',
            'approved_by' => CompanyUser::factory(),
            'approved_at' => $this->faker->dateTimeBetween('-1 week'),
            'admin_notes' => $this->faker->optional()->sentence(),
        ]);
    }

    /**
     * Create a rejected time off request
     */
    public function rejected(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'rejected',
            'approved_by' => CompanyUser::factory(),
            'approved_at' => $this->faker->dateTimeBetween('-1 week'),
            'admin_notes' => $this->faker->sentence(),
        ]);
    }

    /**
     * Create a full-day time off request
     */
    public function fullDay(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_full_day' => true,
            'start_time' => null,
            'end_time' => null,
        ]);
    }

    /**
     * Create a partial day time off request
     */
    public function partialDay(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_full_day' => false,
            'start_time' => $this->faker->time('H:i'),
            'end_time' => $this->faker->time('H:i'),
        ]);
    }
}
