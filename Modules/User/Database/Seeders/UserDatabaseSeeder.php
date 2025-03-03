<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $admin = $this->adminCreation();
        $this->permissionCreation();
        $role = $this->roleCreation();
        $role2 = $this->role2Creation();
        $admin->assignRole($role);
        $this->role3Creation();
    }

    function adminCreation()
    {
        return $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('123456'),
            'is_active' => 1,
        ]);
    }

    function permissionCreation()
    {
        $permissions = [
            ['Index-user', 'User', 'Index'],
            ['Create-user', 'User', 'Create'],
            ['Edit-user', 'User', 'Edit'],
            ['Delete-user', 'User', 'Delete'],

            ['Index-role', 'Roles', 'Index'],
            ['Create-role', 'Roles', 'Create'],
            ['Edit-role', 'Roles', 'Edit'],
            ['Delete-role', 'Roles', 'Delete'],

            ['Index-school', 'School', 'Index'],
            ['Create-school', 'School', 'Create'],
            ['Edit-school', 'School', 'Edit'],
            ['Delete-school', 'School', 'Delete'],

            ['Index-teacher-profile', 'Teacher Profile', 'Index'],
            ['Create-teacher-profile', 'Teacher Profile', 'Create'],
            ['Edit-teacher-profile', 'Teacher Profile', 'Edit'],
            ['Delete-teacher-profile', 'Teacher Profile', 'Delete'],

            ['Index-student', 'Student', 'Index'],
            ['Create-student', 'Student', 'Create'],
            ['Edit-student', 'Student', 'Edit'],
            ['Delete-student', 'Student', 'Delete'],

            ['Index-class', 'Class', 'Index'],
            ['Create-class', 'Class', 'Create'],
            ['Edit-class', 'Class', 'Edit'],
            ['Delete-class', 'Class', 'Delete'],

            ['Index-subject', 'Subject', 'Index'],
            ['Create-subject', 'Subject', 'Create'],
            ['Edit-subject', 'Subject', 'Edit'],
            ['Delete-subject', 'Subject', 'Delete'],

            ['Index-grade', 'Grade', 'Index'],
            ['Create-grade', 'Grade', 'Create'],
            ['Edit-grade', 'Grade', 'Edit'],
            ['Delete-grade', 'Grade', 'Delete'],

            ['Index-grade-category', 'Grade Category', 'Index'],
            ['Create-grade-category', 'Grade Category', 'Create'],
            ['Edit-grade-category', 'Grade Category', 'Edit'],
            ['Delete-grade-category', 'Grade Category', 'Delete'],
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission[0], 'category' => $permission[1], 'guard_name' => 'admin', 'display' => $permission[2]]);
        }
    }

    function roleCreation()
    {
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $permissions = Permission::all();
        $role->syncPermissions($permissions);
        return $role;
    }

    function role2Creation()
    {
        $role = Role::create(['name' => 'School Manager', 'guard_name' => 'admin']);
        $permissions = Permission::where('category', 'Teacher')->get();
        $role->syncPermissions($permissions);
        return $role;
    }
    function role3Creation()
    {
        $role = Role::create(['name' => 'Teacher', 'guard_name' => 'teacher']);
        return $role;
    }
}
