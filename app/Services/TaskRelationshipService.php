<?php

namespace App\Services;

use App\Models\Task;
use App\Models\TaskRelationship;
use App\Models\User;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Repositories\TaskRelationship\TaskRelationshipRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class TaskRelationshipService {
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private TaskRelationshipRepositoryInterface $relationshipRepository
    ) {}

    public function getRelationships(int $taskId) {
        return $this->relationshipRepository->getForTask($taskId);
    }

    public function create(int $taskId, int $relatedTaskId, string $type): TaskRelationship {
        $task = $this->findTask($taskId);
        $relatedTask = $this->findTask($relatedTaskId);

        if ($task->id === $relatedTask->id) {
            throw ValidationException::withMessages([
                'related_task_id' => 'A task cannot have a relationship with itself.',
            ]);
        }

        $workspaceId = $task->list->workspace_id;
        $relatedWorkspaceId = $relatedTask->list->workspace_id;

        if ($workspaceId !== $relatedWorkspaceId) {
            throw ValidationException::withMessages([
                'related_task_id' => 'Both tasks must belong to the same workspace.',
            ]);
        }

        $existing = $this->relationshipRepository->find($taskId, $relatedTaskId, $type);

        if ($existing) {
            throw ValidationException::withMessages([
                'relationship' => 'This relationship already exists.',
            ]);
        }

        if ($type === 'blocks') {
            $this->ensureNoBlockingCycle($taskId, $relatedTaskId);
        }

        return $this->relationshipRepository->create([
            'task_id' => $taskId,
            'related_task_id' => $relatedTaskId,
            'type' => $type,
        ]);
    }

    public function delete(int $taskId, int $relatedTaskId, string $type): bool {
        $task = $this->findTask($taskId);
        $relatedTask = $this->findTask($relatedTaskId);

        $workspaceId = $task->list->workspace_id;
        $relatedWorkspaceId = $relatedTask->list->workspace_id;

        if ($workspaceId !== $relatedWorkspaceId) {
            throw ValidationException::withMessages([
                'related_task_id' => 'Both tasks must belong to the same workspace.',
            ]);
        }

        $relationship = $this->relationshipRepository->find($taskId, $relatedTaskId, $type);

        if (!$relationship) {
            throw (new ModelNotFoundException)->setModel(TaskRelationship::class);
        }

        return $this->relationshipRepository->delete($relationship);
    }

    private function findTask(int $taskId): Task{
        $task = $this->taskRepository->find($taskId);

        if (!$task) {
            throw (new ModelNotFoundException)->setModel(Task::class, [$taskId]);
        }

        return $task;
    }

    private function ensureNoBlockingCycle(int $taskId, int $relatedTaskId): void {
        if ($this->canReach($relatedTaskId, $taskId,[])) {
            throw ValidationException::withMessages([
                'related_task_id' => 'This relationship would create a blocking cycle.',
            ]);
        }
    }

    private function canReach(int $currentTaskId, int $targetTaskId, array $visited): bool {
        if ($currentTaskId === $targetTaskId) {
            return true;
        }

        if (in_array($currentTaskId, $visited, true)) {
            return false;
        }

        $visited[] = $currentTaskId;

        $relations = $this->relationshipRepository->getBlockingRelations($currentTaskId);

        foreach ($relations as $relation) {
            if ($this->canReach($relation->related_task_id, $targetTaskId, $visited)) {
                return true;
            }
        }

        return false;
    }
}