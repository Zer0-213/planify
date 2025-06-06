<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
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

        $admin->syncPermissions(array_map(static fn($p) => $p->value, PermissionEnum::cases()));

        $manager->syncPermissions([
            PermissionEnum::CREATE_SHIFTS,
            PermissionEnum::VIEW_SHIFTS,
            PermissionEnum::ASSIGN_SHIFT
        ]);

        $staff->syncPermissions([
            PermissionEnum::VIEW_SHIFTS
        ]);
    }
}
