<?php

namespace App\Repositories\Permission;

use App\Models\User;

class PermissionRepository implements PermissionRepositoryInterface {
    public function userHasPermission(User $user,string $permission): bool {
        return $user->can($permission);
    }
}

?>