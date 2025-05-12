<?php

namespace Database\Seeders;

use App\Data\Permission;
use DB;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $permissions = [
            new Permission("create_shifts", "Create Shift", "Allows the user to create shifts for a company."),
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->upsert(
                [
                    'name' => $permission->name,
                    'label' => $permission->label,
                    'description' => $permission->description,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                ['name'],
                ['label', 'description']
            );
        }
    }
}
