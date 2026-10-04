<?php

namespace App\Repositories\Task;

use App\Models\Task;

interface TaskRepositoryInterface {
    public function create(array $data);
    public function find(int $taskId): ?Task;
    public function findForRestore(int $id): ?Task;
    public function getListTasks(int $listId);
    public function getWorkspaceTasks(int $workspaceId);
    public function update(Task $task, array $data);
    public function move(Task $task, array $data);
    public function delete(Task $task);
    public function restore(Task $task);
}