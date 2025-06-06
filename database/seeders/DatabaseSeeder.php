<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'zeeshankhn123@outlook.com'],
            [
                'name' => 'Zeeshan',
                'password' => bcrypt('Zeeshan786'),
            ]
        );

        $this->call([
            RolePermissionSeeder::class,
        ]);

    }
}
