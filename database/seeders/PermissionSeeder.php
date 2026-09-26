<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
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
            'download-attachments',
            'view-attachments',
            'delete-attachments',

            'manage-roles',
            'manage-permissions',
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                [
                    'name' => $permission,
                    'guard_name' => 'api',
                ]
            );
        }
    }
}