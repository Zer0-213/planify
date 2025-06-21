<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompanyUser>
 */
class CompanyUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_id' => Company::factory(),
            'user_id' => User::factory(),
        ];
    }

    /**
     * Configure the model factory to be created after the model has been created.
     * Creates a user and company if they don't exist to reduce boilerplate in tests.
     *
     * '
     * @return Factory|CompanyUserFactory
     */
    public function configure(): Factory|CompanyUserFactory
    {
        return $this->afterMaking(function (CompanyUser $companyUser) {
            if (!$companyUser->user_id) {
                $companyUser->user()->associate(User::factory()->create());
            }
            if (!$companyUser->company_id) {
                $companyUser->company()->associate(Company::factory()->create());
            }
        });
    }
}
