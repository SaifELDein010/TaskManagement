<?php

namespace App\Repositories\TaskRelationship;

use App\Models\TaskRelationship;

class TaskRelationshipRepository implements TaskRelationshipRepositoryInterface {
    public function getForTask(int $taskId) {
        return TaskRelationship::with(['task', 'relatedTask',])
            ->where('task_id', $taskId)
            ->orWhere('related_task_id', $taskId)
            ->get();
    }

    public function find(int $taskId, int $relatedTaskId, string $type): ?TaskRelationship {
        return TaskRelationship::where('task_id', $taskId)
            ->where('related_task_id', $relatedTaskId)
            ->where('type', $type)
            ->first();
    }

    public function create(array $data): TaskRelationship {
        return TaskRelationship::create($data);
    }

    public function delete(TaskRelationship $relationship): bool {
        return (bool) $relationship->delete();
    }

    public function getBlockingRelations(int $taskId) {
        return TaskRelationship::where('task_id', $taskId)
            ->where('type', 'blocks')
            ->get();
    }
}