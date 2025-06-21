<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\CompanyUser;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        foreach (PermissionEnum::cases() as $permission) {
            Permission::findOrCreate(
                $permission->value,
                'web'
            );
        }

        $admin = Role::findOrCreate(RoleEnum::ADMIN->value, 'web');
        $manager = Role::findOrCreate(RoleEnum::MANAGER->value, 'web');
        $staff = Role::findOrCreate(RoleEnum::STAFF->value, 'web');

        $adminPermissions = array_map(static fn($p) => $p->value, PermissionEnum::cases());
        $admin->syncPermissions($adminPermissions);

        $managerPermissions = [
            PermissionEnum::CREATE_SHIFTS,
            PermissionEnum::VIEW_SHIFTS,
            PermissionEnum::ASSIGN_SHIFT,
            PermissionEnum::VIEW_ALL_WAGES,
        ];

        $manager->syncPermissions($managerPermissions);


        $staffPermissions = [
            PermissionEnum::VIEW_SHIFTS,
        ];
        $staff->syncPermissions($staffPermissions);


        $this->assignPermissionsToUsers($admin, $adminPermissions);
        $this->assignPermissionsToUsers($manager, $managerPermissions);
        $this->assignPermissionsToUsers($staff, $staffPermissions);
    }


    private function assignPermissionsToUsers(Role $role, array $permissions): void
    {
        $companyUsers = (new CompanyUser)->whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role->name);
        })->get();

        foreach ($companyUsers as $companyUser) {
            foreach ($permissions as $permission) {
                $companyUserPermission = $companyUser->permissions()->where('name', $permission)->first();
                if (!$companyUserPermission) {
                    $companyUser->givePermissionTo($permission);
                }
            }
        }
    }

}
