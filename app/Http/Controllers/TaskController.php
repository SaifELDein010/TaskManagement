<?php

namespace App\Http\Controllers;

use App\Http\Requests\Task\MoveTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Requests\Task\UpdateTaskStatusRequest;
use App\Http\Requests\Task\AssignTaskRequest;
use App\Http\Requests\Task\UpdateTaskPriorityRequest;
use App\Http\Requests\Task\CreateTaskRelationshipRequest;
use App\Http\Requests\Task\DeleteTaskRelationshipRequest;
use App\Services\TaskService;
use App\Services\TaskWorkflowService;
use App\Services\TaskRelationshipService;


class TaskController extends Controller {
    public function __construct(
        private TaskService $taskService,
        private TaskWorkflowService $taskWorkflowService,
        private TaskRelationshipService $taskRelationshipService
    ) {}

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

    public function status(UpdateTaskStatusRequest $request, int $taskId) {
        $task = $this->taskWorkflowService->changeStatus($request->user(), $taskId, $request->string('status')->toString());

        return response()->json($task);
    }

    public function assign(AssignTaskRequest $request, int $taskId) {
        $task = $this->taskWorkflowService->assign($taskId, $request->integer('assigned_to'));

        return response()->json($task);
    }

    public function priority(UpdateTaskPriorityRequest $request, int $taskId) {
        $task = $this->taskWorkflowService->updatePriority($taskId, $request->string('priority'));

        return response()->json($task);
    }

    public function relationships(int $taskId) {
        $relationships = $this->taskRelationshipService->getRelationships($taskId);

        return response()->json($relationships);
    }

    public function createRelationship(CreateTaskRelationshipRequest $request, int $taskId) {
        $relationship = $this->taskRelationshipService->create($taskId, $request->integer('related_task_id'), $request->string('type')->toString());

        return response()->json($relationship, 201);
    }

    public function deleteRelationship(DeleteTaskRelationshipRequest $request, int $taskId) {
        $this->taskRelationshipService->delete($taskId, $request->input('related_task_id'), $request->input('type'));

        return response()->json([
            'message' => 'Relationship deleted successfully.',
        ]);
    }
} 