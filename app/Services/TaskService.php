<?php

namespace App\Services;

use App\Models\Task;
use App\Models\User;
use App\Repositories\Task\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TaskService {
    public function __construct(private TaskRepositoryInterface $taskRepository) {}

    public function create(User $user, array $data): Task {
        $data['created_by'] = $user->id;

        return $this->taskRepository->create($data);
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

    public function delete(int $taskId): bool {
        $task = $this->getTask($taskId);

        return $this->taskRepository->delete($task);
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