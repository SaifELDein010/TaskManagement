<?php

namespace App\Services;

use App\Models\Workspace;
use App\Repositories\Workspace\WorkspaceRepositoryInterface;

class WorkspaceService
{
    public function __construct(
        private WorkspaceRepositoryInterface $workspaceRepository
    ) {
    }

    public function create(array $data): Workspace {
        return $this->workspaceRepository->create($data);
    }
}