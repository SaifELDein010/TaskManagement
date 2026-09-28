<?php

namespace App\Repositories\Workspace;

use App\Models\Workspace;

class WorkspaceRepository implements WorkspaceRepositoryInterface {
    public function create(array $data) {
        return Workspace::create($data); 
    }
}