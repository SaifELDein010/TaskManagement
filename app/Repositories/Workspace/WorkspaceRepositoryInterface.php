<?php

namespace App\Repositories\Workspace;

use App\Models\Workspace;
use App\Models\User;

interface WorkspaceRepositoryInterface {
    public function create(array $data);
    public function list(User $user);
    public function view(User $user, int $id);
    public function update(User $user, Workspace $workspace, array $data): ?Workspace;
    public function delete(User $user, workspace $workspace);
}