<?php

namespace App\Services;

use App\Activitiess\Activity;
use App\Models\ActivityLog;
use App\Repositories\Contracts\ActivityLogRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Jobs\ExportActivityLogJob;

class ActivityLogService{
    public function __construct(private readonly ActivityLogRepositoryInterface $activityLogRepository,) {}

    public function create(int $workspaceId, int $actorId, Activity $activity, int $subjectId,): ActivityLog {
        return $this->activityLogRepository->create([
            'workspace_id' => $workspaceId,
            'actor_id' => $actorId,
            'action' => $activity->action(),
            'subject_type' => $activity->subjectType(),
            'subject_id' => $subjectId,
            'metadata' => $activity->metadata(),
        ]);
    }

    public function getWorkspaceLogs(int $workspaceId, int $perPage = 20,): LengthAwarePaginator {
        return $this->activityLogRepository->getWorkspaceLogs(workspaceId: $workspaceId, perPage: $perPage,);
    }

    public function export(int $workspaceId, string $format,): void {
        ExportActivityLogJob::dispatch(workspaceId: $workspaceId, format: $format,);
    }
}