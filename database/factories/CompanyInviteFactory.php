<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyInvite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompanyInvite>
 */
class CompanyInviteFactory extends Factory
{
    /**
     * Define the model's default state.
     * token is commented out because they should be generated dynamically in tests or services
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'company_id' => Company::factory(),
//            'token' => Hash::make(Str::random(32)),
            'expires_at' => now()->addDays(7), // Default expiration time of 7 days
            'invited_by' => User::factory(),
        ];
    }
}
