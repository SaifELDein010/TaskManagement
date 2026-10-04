<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\WorkspaceMembersController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ListsController;
use App\Http\Controllers\TaskController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', [AuthController::class, 'me']);
    
    Route::patch('/password', [AuthController::class, 'updatePassword']);



    Route::apiResource('roles', RoleController::class);

    Route::put('roles/{role}/permissions', [RoleController::class, 'syncPermissions']);



    Route::post('/workspaces', [WorkspaceController::class, 'store']);

    Route::get('/workspaces', [WorkspaceController::class, 'index']);

    Route::get('/workspaces/{id}', [WorkspaceController::class, 'show']);

    Route::patch('/workspaces/{id}', [WorkspaceController::class, 'update']);

    Route::delete('/workspaces/{id}', [WorkspaceController::class, 'destroy']);

    Route::post('/workspaces/{id}/restore', [WorkspaceController::class, 'restore']);



    Route::get('/workspaces/{id}/members/{userId}', [WorkspaceMembersController::class, 'show']);

    Route::post('/workspaces/{id}/members/{userId}', [WorkspaceMembersController::class, 'store']);

    Route::patch('/workspaces/{id}/members/{userId}', [WorkspaceMembersController::class, 'update']);

    Route::delete('/workspaces/{id}/members/{userId}', [WorkspaceMembersController::class, 'destroy']);



    Route::get('/folders', [FolderController::class, 'index']);

    Route::post('/folders', [FolderController::class, 'store']);

    Route::get('/folders/{id}', [FolderController::class, 'show']);

    Route::patch('/folders/{id}', [FolderController::class, 'update']);

    Route::patch('/folders/{id}/move', [FolderController::class, 'move']);

    Route::delete('/folders/{id}', [FolderController::class, 'destroy']);

    Route::get('/workspaces/{workspaceId}/folders', [FolderController::class, 'workspaceFolders']);

    Route::get('/workspaces/{workspaceId}/folders/tree', [FolderController::class, 'tree']);



    Route::get('/lists', [ListsController::class, 'index']);

    Route::post('/lists', [ListsController::class, 'store']);

    Route::get('/lists/{id}', [ListsController::class, 'show']);

    Route::patch('/lists/{id}', [ListsController::class, 'update']);

    Route::patch('/lists/{id}/move', [ListsController::class, 'move']);

    Route::delete('/lists/{id}', [ListsController::class, 'destroy']);

    Route::post('/lists/{id}/restore', [ListsController::class, 'restore']);



    Route::put('/lists/{id}/workflow', [ListsController::class,'updateWorkflow']);



    Route::post('/lists/{listId}/tasks', [TaskController::class, 'store']);

    Route::get('/tasks/{taskId}', [TaskController::class, 'show']);

    Route::get('/lists/{listId}/tasks', [TaskController::class, 'listTasks']);
    
    Route::get('/workspaces/{workspaceId}/tasks', [TaskController::class, 'workspaceTasks']);

    Route::patch('/tasks/{taskId}', [TaskController::class, 'update']);
    
    Route::post('/tasks/{taskId}/move', [TaskController::class, 'move']);

    Route::delete('/tasks/{taskId}', [TaskController::class, 'destroy']);
    
    Route::post('/tasks/{taskId}/restore', [TaskController::class, 'restore']);
});
