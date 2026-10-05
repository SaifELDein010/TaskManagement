<?php

namespace App\Repositories\TaskRelationship;

use App\Models\TaskRelationship;

interface TaskRelationshipRepositoryInterface{
    public function getForTask(int $taskId);
    public function find(int $taskId, int $relatedTaskId, string $type): ?TaskRelationship;
    public function create(array $data): TaskRelationship;
    public function delete(TaskRelationship $relationship): bool;
    public function getBlockingRelations(int $taskId);
}