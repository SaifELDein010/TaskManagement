<?php

namespace App\Repositories\Workspace;

use App\Models\Workspace;

interface WorkspaceRepositoryInterface {
    public function create(array $data);
}