<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder {
    public function run(): void {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions = [
            'workspace.create',
            'workspace.view',
            'workspace.update',
            'workspace.delete',
            'workspace.restore',
            'workspace.members.view',
            'workspace.members.add',
            'workspace.members.update',
            'workspace.members.remove',

            'folder.create',
            'folder.view',
            'folder.update',
            'folder.delete',

            'list.create',
            'list.view',
            'list.update',
            'list.delete',
            'list.restore',
            'list.update_workflow',

            'task.create',
            'task.view',
            'task.update',
            'task.move',
            'task.delete',
            'task.restore',
            'task.assign',
            'task.change_status',
            'task.change_priority',

            'comment.create',
            'comment.view',
            'comment.update',
            'comment.delete',

            'attachment.upload',
            'attachment.view',
            'attachment.delete',

            'log.get',
            'log.export',
        ];

        foreach ($permissions as $name) {
            Permission::findOrCreate($name, 'api');
        }

        $superAdmin = Role::findOrCreate('super-admin', 'api');
        $admin = Role::findOrCreate('admin', 'api');
        $projectOwner = Role::findOrCreate('project-owner', 'api');
        $projectMember = Role::findOrCreate('project-member', 'api');

        $all = Permission::query()
            ->where('guard_name', 'api')
            ->get();

        $superAdmin->syncPermissions($all);

        $admin->syncPermissions(
            $all->reject(fn ($permission) => in_array(
                $permission->name,
                [
                    'workspace.create',
                    'workspace.update',
                    'workspace.delete',
                    'workspace.restore',
                ],
                true
            ))
        );

        $projectOwner->syncPermissions([
            'workspace.view',
            'folder.create',
            'folder.view',
            'folder.update',
            'folder.delete',
            'list.create',
            'list.view',
            'list.update',
            'list.delete',
            'list.restore',
            'list.update_workflow',
            'task.create',
            'task.view',
            'task.update',
            'task.move',
            'task.delete',
            'task.restore',
            'task.assign',
            'task.change_status',
            'task.change_priority',
            'comment.create',
            'comment.view',
            'comment.update',
            'comment.delete',
            'attachment.upload',
            'attachment.view',
            'attachment.delete',
        ]);

        $projectMember->syncPermissions([
            'workspace.view',
            'list.create',
            'list.view',
            'list.update',
            'list.delete',
            'list.restore',
            'list.update_workflow',
            'task.create',
            'task.view',
            'task.update',
            'task.move',
            'task.delete',
            'task.restore',
            'task.assign',
            'task.change_status',
            'task.change_priority',
            'comment.create',
            'comment.view',
            'comment.update',
            'comment.delete',
            'attachment.upload',
            'attachment.view',
            'attachment.delete',
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
