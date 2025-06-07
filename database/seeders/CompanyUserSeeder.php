<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CompanyUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'zeeshankhn123@outlook.com'],
            [
                'name' => 'Zeeshan',
                'password' => bcrypt('Zeeshan786'),
            ]
        );

        $company = Company::firstOrCreate(
            ['name' => 'Test Company'],
            [
                'owner_id' => $user->id,
                'type' => 'Retail',
                'phone' => '1234567890',
            ]
        );

        /** @var $companyUser CompanyUser */
        $companyUser = (new CompanyUser)->firstOrCreate(
            [
                'user_id' => $user->id,
                'company_id' => $company->id,
            ]
        );

        $adminRole = Role::query()->where('name', RoleEnum::ADMIN)->first();

        if ($adminRole) {
            $companyUser->roles()->sync([$adminRole->id]);

            $permissionIds = $adminRole->permissions()->pluck('id');
            $companyUser->permissions()->sync($permissionIds);
        }
    }
}
