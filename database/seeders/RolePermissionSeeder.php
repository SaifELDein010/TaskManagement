<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    private const GUARD = 'api';

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

            'role.view',
            'role.create',
            'role.update',
            'role.delete',
            'role.permissions.update',

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

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, self::GUARD);
        }

        $superAdmin = Role::findOrCreate('super admin', self::GUARD);
        $workspaceMember = Role::findOrCreate('workspace member', self::GUARD);

        $superAdmin->syncPermissions(Permission::where('guard_name', self::GUARD)->get());

        $workspaceMember->syncPermissions([
            'workspace.view',

            'folder.view',

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