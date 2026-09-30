<?php

namespace App\Services\Role;

use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleService
{
    private const GUARD = 'api';

    public function createRole(array $data): Role {
        $role = Role::create([
            'name' => $data['name'],
            'guard_name' => self::GUARD,
        ]);

        return $role->load('permissions');
    }

    public function getRoles(): Collection {
        return Role::query()
            ->where('guard_name', self::GUARD)
            ->with('permissions')
            ->get();
    }

    public function getRole(Role $role): Role {
        $this->ensureApiGuard($role);

        return $role->load('permissions');
    }

    public function updateRole(Role $role, array $data): Role {
        $this->ensureApiGuard($role);

        $role->update([
            'name' => $data['name'],
        ]);

        return $role->refresh()->load('permissions');
    }

    public function deleteRole(Role $role): void {
        $this->ensureApiGuard($role);

        if ($role->users()->exists()) {
            throw new \DomainException(
                'The role cannot be deleted because it is assigned to one or more users.'
            );
        }

        $role->delete();
    }

    public function syncPermissions(
        Role $role,
        array $permissionNames
    ): Role {
        $this->ensureApiGuard($role);

        $permissions = Permission::query()
            ->where('guard_name', self::GUARD)
            ->whereIn('name', $permissionNames)
            ->get();

        if ($permissions->count() !== count(array_unique($permissionNames))) {
            throw new \DomainException(
                'One or more permissions do not exist for the api guard.'
            );
        }

        $role->syncPermissions($permissions);

        return $role->refresh()->load('permissions');
    }

    private function ensureApiGuard(Role $role): void {
        if ($role->guard_name !== self::GUARD) {
            throw new \DomainException(
                'The specified role does not belong to the api guard.'
            );
        }
    }
}