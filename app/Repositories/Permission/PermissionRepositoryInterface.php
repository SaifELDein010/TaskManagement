<?php

namespace App\Repositories\Permission;

use App\Models\User;

interface PermissionRepositoryInterface {
    public function userHasPermission(User $user,string $permission): bool;
}