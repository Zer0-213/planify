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
        $user = User::factory()->create();
        $user->subscriptions()->create(['type' => 'default', 'paddle_id' => 'default', 'status' => 'active']);
        $company = Company::factory()->create(['owner_id' => $user->id]);
        return [
            'company_id' => $company->id,
            'user_id' => $user->id,
            'is_default' => true,
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
                $user = User::factory()->create();
                $user->subscriptions()->create(['type' => 'default', 'paddle_id' => 'default', 'status' => 'active']);
                $companyUser->user()->associate($user);
            }
            if (!$companyUser->company_id) {
                $companyUser->company()->associate(Company::factory()->create());
            }
        });
    }
}
