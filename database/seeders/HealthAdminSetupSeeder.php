<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HealthAdminSetupSeeder extends Seeder
{
    public function run(): void
    {
        // 🔹 Start fresh: clear roles and permissions
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Role::truncate();
        Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1️⃣ Create Shield super_admin role (full access)
        $superAdminRole = Role::create([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        // 2️⃣ Create standard admin role
        $adminRole = Role::create([
            'name' => 'admin',
            'guard_name' => 'web',
        ]);

        // 3️⃣ List all permissions explicitly
        $permissions = [
            "view_speciality",
            "view_any_speciality",
            "create_speciality",
            "update_speciality",
            "restore_speciality",
            "restore_any_speciality",
            "replicate_speciality",
            "reorder_speciality",
            "delete_speciality",
            "delete_any_speciality",
            "force_delete_speciality",
            "force_delete_any_speciality",
            "view_program",
            "view_any_program",
            "create_program",
            "update_program",
            "restore_program",
            "restore_any_program",
            "replicate_program",
            "reorder_program",
            "delete_program",
            "delete_any_program",
            "force_delete_program",
            "force_delete_any_program",
            "view_project",
            "view_any_project",
            "create_project",
            "update_project",
            "restore_project",
            "restore_any_project",
            "replicate_project",
            "reorder_project",
            "delete_project",
            "delete_any_project",
            "force_delete_project",
            "force_delete_any_project",
            "view_health_reason",
            "view_any_health_reason",
            "create_health_reason",
            "update_health_reason",
            "restore_health_reason",
            "restore_any_health_reason",
            "replicate_health_reason",
            "reorder_health_reason",
            "delete_health_reason",
            "delete_any_health_reason",
            "force_delete_health_reason",
            "force_delete_any_health_reason",
            "view_patient",
            "view_any_patient",
            "create_patient",
            "update_patient",
            "restore_patient",
            "restore_any_patient",
            "replicate_patient",
            "reorder_patient",
            "delete_patient",
            "delete_any_patient",
            "force_delete_patient",
            "force_delete_any_patient",
            "view_symptom",
            "view_any_symptom",
            "create_symptom",
            "update_symptom",
            "restore_symptom",
            "restore_any_symptom",
            "replicate_symptom",
            "reorder_symptom",
            "delete_symptom",
            "delete_any_symptom",
            "force_delete_symptom",
            "force_delete_any_symptom",
            "view_body_section",
            "view_any_body_section",
            "create_body_section",
            "update_body_section",
            "restore_body_section",
            "restore_any_body_section",
            "replicate_body_section",
            "reorder_body_section",
            "delete_body_section",
            "delete_any_body_section",
            "force_delete_body_section",
            "force_delete_any_body_section",
            "view_question",
            "view_any_question",
            "create_question",
            "update_question",
            "restore_question",
            "restore_any_question",
            "replicate_question",
            "reorder_question",
            "delete_question",
            "delete_any_question",
            "force_delete_question",
            "force_delete_any_question",
        ];

        // 4️⃣ Create permissions
        foreach ($permissions as $permName) {
            Permission::create([
                'name' => $permName,
                'guard_name' => 'web',
            ]);
        }

        // 5️⃣ Assign all permissions to admin role
        $adminRole->syncPermissions(Permission::all());

        // 6️⃣ Create default admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('password'), // Change to secure password
            ]
        );

        // 7️⃣ Assign super_admin role to the admin user
        $admin->syncRoles([$superAdminRole]);

        $this->command->info('✅ Health app admin user, roles, and permissions freshly seeded.');
    }
}
