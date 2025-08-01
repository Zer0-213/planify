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
        $adminUser = User::firstOrCreate(
            ['email' => 'zeeshankhn123@outlook.com'],
            [
                'name' => 'Zeeshan',
                'password' => bcrypt('Zeeshan786'),
            ]
        );

        $company = Company::firstOrCreate(
            ['name' => 'Test Company'],
            [
                'owner_id' => $adminUser->id,
                'type' => 'Retail',
                'phone_number' => '1234567890',
            ]
        );

        $users = [
            [
                'email' => 'manager@testcompany.com',
                'name' => 'John Manager',
                'role' => RoleEnum::MANAGER,
            ],
            [
                'email' => 'staff1@testcompany.com',
                'name' => 'Alice Staff',
                'role' => RoleEnum::STAFF,
            ],
            [
                'email' => 'staff2@testcompany.com',
                'name' => 'Bob Staff',
                'role' => RoleEnum::STAFF,
            ],
            [
                'email' => 'staff3@testcompany.com',
                'name' => 'Carol Staff',
                'role' => RoleEnum::STAFF,
            ],
        ];

        $adminCompanyUser = (new CompanyUser)->firstOrCreate([
            'user_id' => $adminUser->id,
            'company_id' => $company->id,
        ]);

        $this->assignRoleToCompanyUser($adminCompanyUser, RoleEnum::ADMIN);

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('password123'),
                ]
            );

            $companyUser = (new CompanyUser)->firstOrCreate([
                'user_id' => $user->id,
                'company_id' => $company->id,
            ]);

            $this->assignRoleToCompanyUser($companyUser, $userData['role']);
        }
    }

    private function assignRoleToCompanyUser(CompanyUser $companyUser, RoleEnum $roleEnum): void
    {
        $role = Role::query()->where('name', $roleEnum->value)->first();

        if ($role) {
            $companyUser->roles()->sync([$role->id]);

            $permissionIds = $role->permissions()->pluck('id');
            $companyUser->permissions()->sync($permissionIds);
        }
    }
}
