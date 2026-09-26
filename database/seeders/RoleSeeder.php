<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {

        Role::updateOrCreate(
            [
                'name' => 'Super Admin',
                'guard_name' => 'api',
            ]
        );

        Role::updateOrCreate(
            [
                'name' => 'Admin',
                'guard_name' => 'api',
            ]
        );

        Role::updateOrCreate(
            [
                'name' => 'Project Owner',
                'guard_name' => 'api',
            ]
        );

        Role::updateOrCreate(
            [
                'name' => 'Project Member',
                'guard_name' => 'api',
            ]
        );

        $superAdmin = Role::updateOrCreate(
            [
                'name' => 'Super Admin',
                'guard_name' => 'api',
            ]
        );

        $admin = Role::updateOrCreate(
            [
                'name' => 'Admin',
                'guard_name' => 'api',
            ]
        );

        $projectOwner = Role::updateOrCreate(
            [
                'name' => 'Project Owner',
                'guard_name' => 'api',
            ]
        );

        $projectMember = Role::updateOrCreate(
            [
                'name' => 'Project Member',
                'guard_name' => 'api',
            ]
        );

        $superAdmin->syncPermissions([
            'manage-users',
            'manage-workspaces',
            'create-folders',
            'view-folders',
            'update-folders',
            'delete-folders',
            'create-lists',
            'view-lists',
            'update-lists',
            'delete-lists',
            'create-tasks',
            'view-tasks',
            'update-tasks',
            'delete-tasks',
            'assign-tasks',
            'change-task-status',
            'change-task-priority',
            'create-comments',
            'view-comments',
            'update-comments',
            'delete-comments',
            'upload-attachments',
            'view-attachments',
            'delete-attachments',
            'manage-roles',
            'manage-permissions',
        ]);

        $admin->syncPermissions([
            'manage-users',
            'manage-workspaces',
            'create-folders',
            'view-folders',
            'update-folders',
            'delete-folders',
            'create-lists',
            'view-lists',
            'update-lists',
            'delete-lists',
            'create-tasks',
            'view-tasks',
            'update-tasks',
            'delete-tasks',
            'assign-tasks',
            'change-task-status',
            'change-task-priority',
            'create-comments',
            'view-comments',
            'update-comments',
            'delete-comments',
            'upload-attachments',
            'view-attachments',
            'delete-attachments',
        ]);

        $projectOwner->syncPermissions([
            'create-folders',
            'view-folders',
            'update-folders',
            'delete-folders',
            'create-lists',
            'view-lists',
            'update-lists',
            'delete-lists',
            'create-tasks',
            'view-tasks',
            'update-tasks',
            'delete-tasks',
            'assign-tasks',
            'change-task-status',
            'change-task-priority',
            'create-comments',
            'view-comments',
            'update-comments',
            'delete-comments',
            'upload-attachments',
            'view-attachments',
            'delete-attachments',
        ]);

        $projectMember->syncPermissions([
            'view-folders',
            'view-lists',
            'view-tasks',
            'update-tasks',
            'change-task-status',
            'create-comments',
            'view-comments',
            'upload-attachments',
            'view-attachments',
        ]);
    }
}