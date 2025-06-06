<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyInvite;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<CompanyInvite>
 */
class CompanyInviteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'company_id' => Company::factory(),
            'token' => Str::random(32),
            'expires_at' => now()->addDays(7), // Default expiration time of 7 days
            'invited_by' => $this->faker->randomElement([1, 2, 3]), // Assuming user IDs 1, 2, and 3 exist
        ];
    }
}
