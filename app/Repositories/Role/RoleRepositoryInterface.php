<?php

namespace App\Repositories\Role;

use App\Models\Role;

interface RoleRepositoryInterface {
    public function findByName(string $name): ?Role;
}