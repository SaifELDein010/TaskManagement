<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\WorkspaceController;
use App\Http\Controllers\WorkspaceMembersController;
use App\Http\Controllers\FolderController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::patch('/auth/password', [AuthController::class, 'updatePassword']);

    Route::apiResource('roles', RoleController::class);

    Route::put('roles/{role}/permissions', [RoleController::class, 'syncPermissions']);


    Route::get(
        '/workspaces/{id}/members/{userId}',
        [WorkspaceMembersController::class, 'show']
    );

    Route::post(
        '/workspaces/{id}/members/{userId}',
        [WorkspaceMembersController::class, 'store']
    );

    Route::patch(
        '/workspaces/{id}/members/{userId}',
        [WorkspaceMembersController::class, 'update']
    );

    Route::delete(
        '/workspaces/{id}/members/{userId}',
        [WorkspaceMembersController::class, 'destroy']
    );

    Route::post(
        '/workspaces',
        [WorkspaceController::class, 'store']
    );

    Route::get(
        '/workspaces',
        [WorkspaceController::class, 'index']
    );

    Route::get(
        '/workspaces/{id}',
        [WorkspaceController::class, 'show']
    );

    Route::patch(
        '/workspaces/{id}',
        [WorkspaceController::class, 'update']
    );

    Route::delete(
        '/workspaces/{id}',
        [WorkspaceController::class, 'destroy']
    );

    Route::post(
        '/workspaces/{id}/restore',
        [WorkspaceController::class, 'restore']
    );

     Route::get(
        '/folders',
        [FolderController::class, 'index']
    );

    Route::post(
        '/folders',
        [FolderController::class, 'store']
    );

    Route::get(
        '/folders/{id}',
        [FolderController::class, 'show']
    );

    Route::patch(
        '/folders/{id}',
        [FolderController::class, 'update']
    );

    Route::patch(
        '/folders/{id}/move',
        [FolderController::class, 'move']
    );

    Route::delete(
        '/folders/{id}',
        [FolderController::class, 'destroy']
    );

    Route::get(
        '/workspaces/{workspaceId}/folders',
        [FolderController::class, 'workspaceFolders']
    );

    Route::get(
        '/workspaces/{workspaceId}/folders/tree',
        [FolderController::class, 'tree']
    );
});
