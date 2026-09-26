<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::patch('/auth/password', [AuthController::class, 'updatePassword']);
    
    Route::apiResource('roles', RoleController::class);

    Route::put('/roles/{role}/permissions', [RoleController::class, 'syncPermissions']);
});
