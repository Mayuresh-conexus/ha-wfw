<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class HealthAdminSetupSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Create Shield super_admin role (full access)
        $superAdminRole = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        // 2️⃣ Create standard admin role
        $adminRole = Role::firstOrCreate([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        // 3️⃣ List all permissions for models with 1
        $models = [
            'Specialities',
            'Programs',
            'Projects',
            'Health Reasons',
            'Patients',
            'Symptoms',
            'Body Sections',
            'Questions',
        ];

        $actions = [
            'view', 'view_any', 'create', 'update', 'restore', 'restore_any', 
            'replicate', 'reorder', 'delete', 'delete_any', 'force_delete', 'force_delete_any'
        ];

        $permissions = [];

        foreach ($models as $model) {
            $modelSlug = strtolower(str_replace(' ', '_', $model));
            foreach ($actions as $action) {
                $permissions[] = "{$action}_{$modelSlug}";
            }
        }

        // 4️⃣ Create permissions if they don't exist
        foreach ($permissions as $permName) {
            Permission::firstOrCreate([
                'name' => $permName,
                'guard_name' => 'web',
            ]);
        }

        // 5️⃣ Assign all permissions to admin role
        $adminRole->syncPermissions(Permission::all());

        // 6️⃣ Create default admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin',
                'password' => bcrypt('password'), // Change to secure password
            ]
        );

        // 7️⃣ Assign super_admin role to the admin user
        if (!$admin->hasRole($superAdminRole)) {
            $admin->assignRole($superAdminRole);
        }

        $this->command->info('✅ Health app admin user, roles, and permissions setup complete.');
    }
}
