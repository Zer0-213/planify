<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'owner_id' => User::factory(),
            'type' => $this->faker->randomElement(['LLC', 'Corporation', 'Partnership', 'Sole Proprietorship', 'Non-Profit'])

        ];

    }
}
