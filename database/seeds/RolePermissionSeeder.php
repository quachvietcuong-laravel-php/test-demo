<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create Roles
        $roleSuperAdmin = Role::create(['name' => 'super admin']);
        $roleAdmin      = Role::create(['name' => 'admin']);
        $roleEditor     = Role::create(['name' => 'editor']);
        $roleWriter     = Role::create(['name' => 'writer']);
        $roleUser       = Role::create(['name' => 'anonymous']);

        // Permission List as Array
        $permissionList = [

            // Dashboard
            [
                'group_name' => 'dashboard',
                'permissions_name' => [
                    'dashboard.view',
                    'dashboard.edit',
                ],
            ],

            // Blog Permissions
        	[
                'group_name' => 'blog',
                'permissions_name' => [
                    'blog.create',
                    'blog.view',
                    'blog.edit',
                    'blog.delete',
                ],
            ],


        	// Admin Permissions
            [
                'group_name' => 'admin',
                'permissions_name' => [
                    'admin.create',
                    'admin.view',
                    'admin.edit',
                    'admin.delete',
                ],
            ],
        	

        	// Profile Permissions
            [
                'group_name' => 'profile',
                'permissions_name' => [
                    'profile.view',
                	'profile.edit',
                ],
            ],

            // Role Permissions
            [
                'group_name' => 'role',
                'permissions_name' => [
                    'role.create',
                    'role.view',
                    'role.edit',
                    'role.delete',
                ],
            ],
        	
        ];

        // Create and Assign Permissions
        // Permission::create(['name' => 'dashboard.view']);

        foreach ($permissionList as $permissionItem) {
            $permissionGroupName = $permissionItem['group_name'];
            $permissionNames     = $permissionItem['permissions_name'];

            foreach ($permissionNames as $permissionName) {
                $permission = Permission::create([
                    'name'       => $permissionName, 
                    'group_name' => $permissionGroupName,
                ]);
            }
        }
    }
}
