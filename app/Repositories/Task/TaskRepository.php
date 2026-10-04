<?php

namespace App\Repositories\Task;

use App\Models\Task;

class TaskRepository implements TaskRepositoryInterface {
    public function create(array $data) {
        return Task::create($data);
    }

    public function find(int $taskId): ?Task {
        return Task::find($taskId);
    }

    public function findForRestore(int $id): ?Task {
        return Task::withTrashed()->find($id);
    }

    public function getListTasks(int $listId) {
        return Task::where('list_id', $listId)->get();
    }

    public function getWorkspaceTasks(int $workspaceId) {
        return Task::whereHas('list', function ($query) use ($workspaceId) {
            $query->where('workspace_id', $workspaceId);
        })->get();
    }

    public function update(Task $task, array $data) {
        $task->update($data);

        return $task->refresh();
    }

    public function move(Task $task, array $data) {
        $task->update($data);

        return $task->refresh();
    }

    public function delete(Task $task) {
        return $task->delete();
    }

    public function restore(Task $task) {
        $task->restore();
        
        return $task->refresh();
    }
}