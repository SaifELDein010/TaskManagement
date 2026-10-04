<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\MoveTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Services\TaskService;

class TaskController extends Controller {
    public function __construct(private TaskService $taskService) {}

    public function store(StoreTaskRequest $request, int $listId) {
        $task = $this->taskService->create($request->user(), array_merge($request->validated(), ['list_id' => $listId]));

        return response()->json([
                'message' => 'Task created successfully.',
                'data' => $task
            ], 201);
    }

    public function show(int $taskId) {
        $task = $this->taskService->getTask($taskId);

        return response()->json([
                'message' => 'task found.',
                'data'=> $task
            ]);
    }

    public function listTasks(int $listId) {
        $tasks = $this->taskService->getListTasks($listId);

        return response()->json([
                'message' => 'tasks in list found.',
                'data'=> $tasks
            ]);
    }

    public function workspaceTasks(int $workspaceId) {
        $tasks = $this->taskService->getWorkspaceTasks($workspaceId);

        return response()->json([
                'message' => 'tasks in workspace found.',
                'data'=> $tasks
            ]);
    }

    public function update(UpdateTaskRequest $request, int $taskId) {
        $task = $this->taskService->update($taskId, $request->validated());

        return response()->json([
                'message' => 'task updated successfully.',
                'data'=> $task
            ]);
    }

    public function move(MoveTaskRequest $request, int $taskId) {
        $task = $this->taskService->move($taskId, $request->validated());

        return response()->json([
                'message' => 'task move successfully.',
                'data'=> $task
            ]);
    }

    public function destroy(int $taskId) {
        $this->taskService->delete($taskId);

        return response()->json([
            'message' => 'Task deleted successfully.',
        ]);
    }

    public function restore(int $taskId) {
        $task = $this->taskService->restore($taskId);

        return response()->json([
                'message' => 'task restore successfully.',
                'data'=> $task
            ]);
    }
}