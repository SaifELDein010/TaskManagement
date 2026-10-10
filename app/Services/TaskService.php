<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Repositories\Task\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogService;
use App\Activities\Task\TaskCreated;
use App\Activities\Task\TaskDeleted;

class TaskService {
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private ActivityLogService $activityLogService,
    ) {}

    public function create(User $user, array $data): Task {
        return DB::transaction(function () use ($user, $data) {
            $data['created_by'] = $user->id;

            $task = $this->taskRepository->create($data);

            $workspaceId = $task->list->workspace_id;

            $this->activityLogService->create(
                workspaceId: $workspaceId,
                actorId: $user->id,
                activity: new TaskCreated(),
                subjectId: $task->id,
            );

            return $task;
        });
    }

    public function getTask(int $taskId): Task {
        $task = $this->taskRepository->find($taskId);

        if (!$task) {
            throw (new ModelNotFoundException)->setModel(Task::class, [$taskId]);
        }

        return $task;
    }

    public function getListTasks(int $listId) {
        return $this->taskRepository->getListTasks($listId);
    }

    public function getWorkspaceTasks(int $workspaceId) {
        return $this->taskRepository->getWorkspaceTasks($workspaceId);
    }

    public function update(int $taskId, array $data): Task {
        $task = $this->getTask($taskId);

        return $this->taskRepository->update($task, $data);
    }

    public function move(int $taskId, array $data): Task {
        $task = $this->getTask($taskId);

        return $this->taskRepository->move($task, $data);
    }

    public function delete(User $user, int $taskId): bool {
        return DB::transaction(function () use ($user, $taskId) {
            $task = $this->getTask($taskId);

            $workspaceId = $task->list->workspace_id;

            $result = $this->taskRepository->delete($task);

            $this->activityLogService->create(
                workspaceId: $workspaceId,
                actorId: $user->id,
                activity: new TaskDeleted(),
                subjectId: $task->id,
            );

            return $result;
        });
    }

    public function restore(int $taskId): Task {
        $task = $this->taskRepository->findForRestore($taskId);

        if (!$task) {
            throw (new ModelNotFoundException)->setModel(Task::class, [$taskId]);
        }

        $this->taskRepository->restore($task);

        return $task;
    }
}