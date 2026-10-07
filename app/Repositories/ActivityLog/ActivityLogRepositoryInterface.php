<?php

namespace App\Repositories\Contracts;

use App\Models\ActivityLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ActivityLogRepositoryInterface {
    public function create(array $data): ActivityLog;
    public function getWorkspaceLogs(int $workspaceId, int $perPage = 20,): LengthAwarePaginator;
    public function getWorkspaceLogsForExport(int $workspaceId): Collection;    
}