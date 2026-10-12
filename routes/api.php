<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RoleController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\WorkspaceMembersController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\ListsController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\AttachmentsController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\NotificationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', [AuthController::class, 'me']);

    Route::patch('/password', [AuthController::class, 'updatePassword']);



    Route::apiResource('roles', RoleController::class)->middleware('authorize.action:roles.manage');

    Route::put('roles/{role}/permissions', [RoleController::class, 'syncPermissions'])->middleware('authorize.action:roles.manage');



    Route::post('/workspaces', [WorkspaceController::class, 'store'])->middleware('authorize.action:workspace.create');

    Route::get('/workspaces', [WorkspaceController::class, 'index'])->middleware('authorize.action:workspace.view');

    Route::get('/workspaces/{id}', [WorkspaceController::class, 'show'])->middleware('authorize.action:workspace.view,workspace,id');

    Route::patch('/workspaces/{id}', [WorkspaceController::class, 'update'])->middleware('authorize.action:workspace.update,workspace,id');

    Route::delete('/workspaces/{id}', [WorkspaceController::class, 'destroy'])->middleware('authorize.action:workspace.delete,workspace,id');

    Route::post('/workspaces/{id}/restore', [WorkspaceController::class, 'restore'])->middleware('authorize.action:workspace.restore,workspace,id');



    Route::get('/workspaces/{id}/members/{userId}', [WorkspaceMembersController::class, 'show'])->middleware('authorize.action:workspace.members.view,workspace,id');

    Route::post('/workspaces/{id}/members/{userId}', [WorkspaceMembersController::class, 'store'])->middleware('authorize.action:workspace.members.add,workspace,id');

    Route::patch('/workspaces/{id}/members/{userId}', [WorkspaceMembersController::class, 'update'])->middleware('authorize.action:workspace.members.update,workspace,id');

    Route::delete('/workspaces/{id}/members/{userId}', [WorkspaceMembersController::class, 'destroy'])->middleware('authorize.action:workspace.members.remove,workspace,id');



    Route::get('/folders', [FolderController::class, 'index'])->middleware('authorize.action:folder.view');

    Route::post('/folders', [FolderController::class, 'store'])->middleware('authorize.action:folder.create');

    Route::get('/folders/{id}', [FolderController::class, 'show'])->middleware('authorize.action:folder.view,folder,id');

    Route::patch('/folders/{id}', [FolderController::class, 'update'])->middleware('authorize.action:folder.update,folder,id');

    Route::patch('/folders/{id}/move', [FolderController::class, 'move'])->middleware('authorize.action:folder.update,folder,id');

    Route::delete('/folders/{id}', [FolderController::class, 'destroy'])->middleware('authorize.action:folder.delete,folder,id');

    Route::get('/workspaces/{workspaceId}/folders', [FolderController::class, 'workspaceFolders'])->middleware('authorize.action:folder.view,workspace,workspaceId');

    Route::get('/workspaces/{workspaceId}/folders/tree', [FolderController::class, 'tree'])->middleware('authorize.action:folder.view,workspace,workspaceId');



    Route::get('/lists', [ListsController::class, 'index'])->middleware('authorize.action:list.view');

    Route::post('/lists', [ListsController::class, 'store'])->middleware('authorize.action:list.create');

    Route::get('/lists/{id}', [ListsController::class, 'show'])->middleware('authorize.action:list.view,list,id');

    Route::patch('/lists/{id}', [ListsController::class, 'update'])->middleware('authorize.action:list.update,list,id');

    Route::patch('/lists/{id}/move', [ListsController::class, 'move'])->middleware('authorize.action:list.update,list,id');

    Route::delete('/lists/{id}', [ListsController::class, 'destroy'])->middleware('authorize.action:list.delete,list,id');

    Route::post('/lists/{id}/restore', [ListsController::class, 'restore'])->middleware('authorize.action:list.restore,list,id');



    Route::put('/lists/{id}/workflow', [ListsController::class, 'updateWorkflow'])->middleware('authorize.action:list.update_workflow,list,id');



    Route::post('/lists/{listId}/tasks', [TaskController::class, 'store'])->middleware('authorize.action:task.create,list,listId');

    Route::get('/tasks/{taskId}', [TaskController::class, 'show'])->middleware('authorize.action:task.view,task,taskId');

    Route::get('/lists/{listId}/tasks', [TaskController::class, 'listTasks'])->middleware('authorize.action:task.view,list,listId');

    Route::get('/workspaces/{workspaceId}/tasks', [TaskController::class, 'workspaceTasks'])->middleware('authorize.action:task.view,workspace,workspaceId');

    Route::patch('/tasks/{taskId}', [TaskController::class, 'update'])->middleware('authorize.action:task.update,task,taskId');

    Route::post('/tasks/{taskId}/move', [TaskController::class, 'move'])->middleware('authorize.action:task.move,task,taskId');

    Route::delete('/tasks/{taskId}', [TaskController::class, 'destroy'])->middleware('authorize.action:task.delete,task,taskId');

    Route::post('/tasks/{taskId}/restore', [TaskController::class, 'restore'])->middleware('authorize.action:task.restore,task,taskId');



    Route::post('/tasks/{taskId}/assignee', [TaskController::class, 'assign'])->middleware('authorize.action:task.assign,task,taskId');

    Route::post('/tasks/{taskId}/priority', [TaskController::class, 'priority'])->middleware('authorize.action:task.change_priority,task,taskId');

    Route::post('/tasks/{taskId}/status', [TaskController::class, 'status'])->middleware('authorize.action:task.change_status,task,taskId');

    Route::get('/tasks/{taskId}/relationships', [TaskController::class, 'relationships'])->middleware('authorize.action:task.view,task,taskId');

    Route::post('/tasks/{taskId}/relationships', [TaskController::class, 'createRelationship'])->middleware('authorize.action:task.update,task,taskId');

    Route::delete('/tasks/{taskId}/relationships', [TaskController::class, 'deleteRelationship'])->middleware('authorize.action:task.update,task,taskId');



    Route::post('/tasks/{taskId}/comments', [CommentsController::class, 'store'])->middleware('authorize.action:comment.create,task,taskId');

    Route::get('/tasks/{taskId}/comments', [CommentsController::class, 'index'])->middleware('authorize.action:comment.view,task,taskId');

    Route::patch('/comments/{comment}', [CommentsController::class, 'update'])->middleware('authorize.action:comment.update,comment,comment');

    Route::delete('/comments/{comment}', [CommentsController::class, 'destroy'])->middleware('authorize.action:comment.delete,comment,comment');



    Route::post('/tasks/{taskId}/attachments', [AttachmentsController::class, 'store'])->middleware('authorize.action:attachment.upload,task,taskId');

    Route::get('/tasks/{taskId}/attachments', [AttachmentsController::class, 'index'])->middleware('authorize.action:attachment.view,task,taskId');

    Route::get('/attachments/{attachment}/download', [AttachmentsController::class, 'download'])->middleware('authorize.action:attachment.view,attachment,attachment');

    Route::delete('/attachments/{attachment}', [AttachmentsController::class, 'destroy'])->middleware('authorize.action:attachment.delete,attachment,attachment');



    Route::get('/workspaces/{workspace}/activity', [ActivityLogController::class, 'index'])->middleware('authorize.action:log.get,workspace,workspace');

    Route::post('/workspaces/{workspaceId}/activity/export', [ActivityLogController::class, 'export'])->middleware('authorize.action:log.export,workspace,workspaceId');



    Route::get('/notifications', [NotificationController::class, 'index']);

    Route::get('/notification-preferences', [NotificationController::class, 'preferences']);

    Route::put('/notification-preferences', [NotificationController::class, 'updatePreferences']);
});