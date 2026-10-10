<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Repositories\StatusHistory\StatusHistoryRepositoryInterface;
use App\Repositories\WorkspaceMember\WorkspaceMemberRepositoryInterface;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class TaskWorkflowService{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private WorkspaceMemberRepositoryInterface $workspaceMemberRepository,
        private StatusHistoryRepositoryInterface $statusHistoryRepository
    ) {}

    public function changeStatus(User $user, int $taskId, string $newStatus): Task {
        return DB::transaction(function () use ($user, $taskId, $newStatus) {
            $task = $this->taskRepository->find($taskId);

            if (!$task) {
                throw (new ModelNotFoundException)->setModel(Task::class, [$taskId]);
            }

            $oldStatus = $task->status;

            if ($oldStatus === $newStatus) {
                throw ValidationException::withMessages([
                    'status' => 'The task is already in this status.',
                ]);
            }

            $this->validateTransition($oldStatus, $newStatus);

            $task = $this->taskRepository->update($task,['status' => $newStatus,]);

            $this->statusHistoryRepository->create([
                'task_id' => $task->id,
                'new_status' => $newStatus,
                'changed_by' => $user->id,
            ]);

            return $task;
        });
    }

    private function validateTransition(string $from, string $to): void {
        $transitions = [
            'pending' => ['in_progress', 'cancelled',],

            'in_progress' => ['review', 'cancelled',],

            'review' => ['in_progress', 'completed',],

            'completed' => [],

            'cancelled' => [],
        ];

        if (!in_array($to, $transitions[$from] ?? [], true)) {
            throw ValidationException::withMessages([
                'status' => "Cannot change task status from {$from} to {$to}.",
            ]);
        }
    }

    public function updatePriority(int $taskId, string $priority): Task {
        $task = $this->taskRepository->find($taskId);

        if (!$task) {
            throw (new ModelNotFoundException)->setModel(Task::class, [$taskId]);
        }

        return $this->taskRepository->update($task,['priority' => $priority,]);
    }

    public function assign(int $taskId, int $assigneeId): Task {
        $task = $this->taskRepository->find($taskId);

        if (!$task) {
            throw (new ModelNotFoundException)->setModel(Task::class, [$taskId]);
        }

        $workspaceId = $task->list->workspace_id;

        $workspaceMember = $this->workspaceMemberRepository->find($workspaceId, $assigneeId);

        if (!$workspaceMember) {
            throw ValidationException::withMessages([
                'assigned_to' => 'The selected user is not a member of this workspace.',
            ]);
        }

        return $this->taskRepository->update($task,['assigned_to' => $assigneeId,]  );
    }
}