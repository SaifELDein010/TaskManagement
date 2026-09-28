<?php

namespace App\Services\Policy;

use App\Models\User;
use App\Repositories\Permission\PermissionRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;

class PolicyService {
    public function __construct(
        private readonly PermissionRepositoryInterface $permissionRepository,
        private readonly RoleRepositoryInterface $roleRepository,
    ) {
    }

    public function hasPermission(User $user, string $permission): bool {
        return $this->permissionRepository->userHasPermission(
            $user,
            $permission
        );
    }

    public function hasRole(User $user, string $role): bool {
        return $this->roleRepository->userHasRole(
            $user,
            $role
        );
    }

    public function authorize(User $user, string $permission): bool {
        return $this->hasPermission(
            $user,
            $permission
        );
    }
}