<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $userRole = Role::firstOrCreate(['name' => 'user']);

        // create admin user
        $user = User::firstOrCreate(
            ['email' => 'manuelsansoresg@gmail.com'],
            [
                'name' => 'Manuel Sansores',
                'password' => Hash::make('demor00txx'),
            ]
        );

        $user->assignRole($adminRole);
    }
}
