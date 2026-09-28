<?php

namespace App\Repositories\Role;

use App\Models\User;

interface RoleRepositoryInterface {
    public function userHasRole(User $user, string $role): bool;
}