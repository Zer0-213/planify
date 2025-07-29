<?php

namespace App\Console\Commands;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use App\Models\CompanyUser;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class SyncRolesAndPermissions extends Command
{
    protected $signature = 'sync:perms';
    protected $description = 'Sync roles and permissions from enums and assign them to users';

    public function handle(): void
    {
        $this->info('🔄 Syncing permissions...');
        foreach (PermissionEnum::cases() as $permission) {
            Permission::findOrCreate($permission->value, 'web');
            $this->line("✅ Permission synced: {$permission->value}");
        }

        $this->info('🔄 Syncing roles...');
        $admin = Role::findOrCreate(RoleEnum::ADMIN->value, 'web');
        $manager = Role::findOrCreate(RoleEnum::MANAGER->value, 'web');
        $staff = Role::findOrCreate(RoleEnum::STAFF->value, 'web');

        $this->info('Syncing permissions to roles...');

        $adminPermissions = [
            PermissionEnum::INVITE_USER->value,
            PermissionEnum::CREATE_SHIFTS->value,
            PermissionEnum::VIEW_SHIFTS->value,
            PermissionEnum::ASSIGN_SHIFT->value,
            PermissionEnum::MANAGE_COMPANY->value,
            PermissionEnum::VIEW_ALL_WAGES->value,
            PermissionEnum::DELETE_STAFF_MEMBER->value,
            PermissionEnum::UPDATE_STAFF_MEMBER->value,
            PermissionEnum::VIEW_TIME_OFF_REQUESTS->value,
            PermissionEnum::MANAGE_TIME_OFF_REQUESTS->value,
        ];

        $managerPermissions = [
            PermissionEnum::CREATE_SHIFTS,
            PermissionEnum::VIEW_SHIFTS,
            PermissionEnum::ASSIGN_SHIFT,
            PermissionEnum::VIEW_ALL_WAGES,
            PermissionEnum::INVITE_USER,
            PermissionEnum::UPDATE_STAFF_MEMBER,
            PermissionEnum::DELETE_STAFF_MEMBER,
            PermissionEnum::VIEW_TIME_OFF_REQUESTS,
            PermissionEnum::MANAGE_TIME_OFF_REQUESTS,
        ];

        $staffPermissions = [
            PermissionEnum::VIEW_SHIFTS,
            PermissionEnum::REQUEST_TIME_OFF,
        ];


        $admin->syncPermissions($adminPermissions);
        $this->line("🔗 Admin permissions synced");

        $manager->syncPermissions(array_map(fn($p) => $p->value, $managerPermissions));
        $this->line("🔗 Manager permissions synced");

        $staff->syncPermissions(array_map(fn($p) => $p->value, $staffPermissions));
        $this->line("🔗 Staff permissions synced");

        $this->info('👥 Assigning permissions to users...');
        $this->assignPermissionsToUsers($admin, $adminPermissions);
        $this->assignPermissionsToUsers($manager, array_map(fn($p) => $p->value, $managerPermissions));
        $this->assignPermissionsToUsers($staff, array_map(fn($p) => $p->value, $staffPermissions));

        $this->info('✅ Role and permission sync complete.');
    }

    private function assignPermissionsToUsers(Role $role, array $permissions): void
    {
        $companyUsers = CompanyUser::whereHas('roles', function ($query) use ($role) {
            $query->where('name', $role->name);
        })->get();

        foreach ($companyUsers as $companyUser) {
            foreach ($permissions as $permission) {
                if (!$companyUser->hasPermissionTo($permission)) {
                    $companyUser->givePermissionTo($permission);
                    $this->line("  → Assigned '{$permission}' to user ID {$companyUser->id}");
                }
            }
        }
    }
}
