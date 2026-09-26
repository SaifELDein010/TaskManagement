<?php

namespace App\Services;

use RuntimeException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleService
{
    public function create(array $data): Role {
        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => 'api',
        ]);

        return $role->load('permissions');
    }

    public function getAll(): \Illuminate\Database\Eloquent\Collection {
        return Role::with('permissions')
            ->where('guard_name', 'api')
            ->get();
    }

    public function getById(int $id): Role {
        $role = Role::with('permissions')
            ->where('guard_name', 'api')
            ->find($id);

        if (!$role) {
            throw new RuntimeException('Role not found.');
        }

        return $role;
    }

    public function update(Role $role, array $data): Role {
        if ($role->guard_name !== 'api') {
            throw new RuntimeException('Guard mismatch.');
        }

        $role->update([
            'name' => $data['name'],
        ]);

        return $role->load('permissions');
    }

    public function syncPermissions(Role $role, array $permissionNames): Role {
        if ($role->guard_name !== 'api') {
            throw new RuntimeException('Guard mismatch.');
        }

        $permissionNames = array_values(
            array_unique($permissionNames)
        );

        $permissions = Permission::where('guard_name', 'api')
            ->whereIn('name', $permissionNames)
            ->get();

        if ($permissions->count() !== count($permissionNames)) {
            $found = $permissions->pluck('name')->all();

            $missing = array_values(
                array_diff($permissionNames, $found)
            );

            throw new RuntimeException(
                'Permission not found: ' . implode(', ', $missing)
            );
        }

        $role->syncPermissions($permissions);

        return $role->load('permissions');
    }

    public function delete(Role $role): void {
        if ($role->guard_name !== 'api') {
            throw new RuntimeException('Guard mismatch.');
        }

        if ($role->users()->exists()) {
            throw new RuntimeException(
                'Cannot delete a role assigned to users.'
            );
        }

        $role->delete();
    }
}