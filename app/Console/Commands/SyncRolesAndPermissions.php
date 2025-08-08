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
    protected $signature = 'sync:perms {--cleanup : Delete permissions and roles not in enums}';
    protected $description = 'Sync roles and permissions from enums and assign them to users';

    public function handle(): void
    {
        if ($this->option('cleanup')) {
            $this->cleanupOrphanedPermissionsAndRoles();
        }

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
            PermissionEnum::DELETE_COMPANY->value,
            PermissionEnum::DISABLE_COMPANY->value,
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
            PermissionEnum::REQUEST_TIME_OFF
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

    private function cleanupOrphanedPermissionsAndRoles(): void
    {
        $this->info('🧹 Cleaning up orphaned permissions and roles...');

        // Get valid permission and role names from enums
        $validPermissions = collect(PermissionEnum::cases())->pluck('value')->toArray();
        $validRoles = collect(RoleEnum::cases())->pluck('value')->toArray();

        // Find orphaned permissions
        $orphanedPermissions = Permission::whereNotIn('name', $validPermissions)
            ->where('guard_name', 'web')
            ->get();

        foreach ($orphanedPermissions as $permission) {
            $this->warn("🗑️  Deleting orphaned permission: {$permission->name}");

            // Remove permission from all users and roles before deleting
            $permission->users()->detach();
            $permission->roles()->detach();
            $permission->delete();
        }

        // Find orphaned roles
        $orphanedRoles = Role::query()->whereNotIn('name', $validRoles)
            ->where('guard_name', 'web')
            ->get();

        foreach ($orphanedRoles as $role) {
            $this->warn("🗑️  Deleting orphaned role: {$role->name}");

            // Remove role from all users before deleting
            $role->users()->detach();
            $role->permissions()->detach();
            $role->delete();
        }

        $permissionCount = $orphanedPermissions->count();
        $roleCount = $orphanedRoles->count();

        if ($permissionCount > 0 || $roleCount > 0) {
            $this->info("✅ Cleanup complete: {$permissionCount} permissions and {$roleCount} roles removed.");
        } else {
            $this->info("✅ No orphaned permissions or roles found.");
        }
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
