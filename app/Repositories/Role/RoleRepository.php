<?php

namespace App\Repositories\Role;

use App\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function findByName(string $name): ?Role {
        return Role::where('name', $name)->first();
    }
}