<?php

namespace App\Repositories;

use App\Models\ActivityLog;
use App\Repositories\Contracts\ActivityLogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ActivityLogRepository implements ActivityLogRepositoryInterface {
    public function create(array $data): ActivityLog {
        return ActivityLog::query()->create($data);
    }

    public function getWorkspaceLogs(int $workspaceId, int $perPage = 20,): LengthAwarePaginator {
        return ActivityLog::query()
            ->with(['actor', 'subject',])
            ->where('workspace_id', $workspaceId)
            ->latest('created_at')
            ->paginate($perPage);
    }

    public function getWorkspaceLogsForExport(int $workspaceId): Collection {
        return ActivityLog::query()
            ->with(['actor', 'subject',])
            ->where('workspace_id', $workspaceId)
            ->oldest('created_at')
            ->get();
    }
}   