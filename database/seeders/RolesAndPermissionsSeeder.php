<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions (examples)
        Permission::findOrCreate('manage users', 'web');
        Permission::findOrCreate('manage forms', 'web');
        Permission::findOrCreate('manage pages', 'web');
        Permission::findOrCreate('manage translations', 'web');
        Permission::findOrCreate('view dashboard', 'web');

        // create roles and assign created permissions

        // Editor role
        $role = Role::findOrCreate('editor', 'web');
        $editorPermissions = Permission::whereIn('name', ['view dashboard', 'manage pages', 'manage translations', 'manage forms'])
            ->where('guard_name', 'web')
            ->get();
        $role->syncPermissions($editorPermissions);

        // Super-Admin role
        $role = Role::findOrCreate('super-admin', 'web');
        $role->syncPermissions(Permission::where('guard_name', 'web')->get());

        // Quality role
        Role::findOrCreate('quality', 'web');

        // Ensure default admin user exists and has super-admin role
        $admin = User::firstOrCreate(
            ['email' => 'admin@termosalud.com'],
            [
                'name' => 'Admin Termosalud',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('super-admin');

        // Create an editor user
        $editor = User::firstOrCreate(
            ['email' => 'editor@termosalud.com'],
            [
                'name' => 'Editor Termosalud',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $editor->assignRole('editor');
    }
}
