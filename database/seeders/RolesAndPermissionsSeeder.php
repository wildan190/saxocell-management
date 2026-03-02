<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $roles = [
            'owner',
            'audit',
            'purchasing',
            'crewstore',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create the owner user
        $owner = User::firstOrCreate(
            ['email' => 'owner@saxocell.id'],
            [
                'name' => 'System Owner',
                'password' => Hash::make('password'),
                'role' => 'owner', // Retaining the string field just in case
                'is_active' => true,
            ]
        );

        // Assign Spatie role
        if (!$owner->hasRole('owner')) {
            $owner->assignRole('owner');
        }
    }
}
