<?php

namespace App\Repositories\Role;

use App\Models\User;

class RoleRepository implements RoleRepositoryInterface {
    public function userHasRole(User $user, string $role): bool {
        return $user->hasRole($role);
    }
}