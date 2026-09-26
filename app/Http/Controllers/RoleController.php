<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\SyncRolePermissionsRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function __construct(
        private RoleService $roleService
    ) {
    }

    public function index(): JsonResponse {
        return response()->json([
            'roles' => $this->roleService->getAll(),
        ], 200);
    }

    public function store(StoreRoleRequest $request): JsonResponse {
        $role = $this->roleService->create(
            $request->validated()
        );

        return response()->json([
            'message' => 'Role created successfully',
            'role' => $role,
        ], 201);
    }

    public function show(Role $role): JsonResponse {
        $role = $this->roleService->getById($role->id);

        return response()->json([
            'role' => $role,
        ], 200);
    }

    public function update(UpdateRoleRequest $request, Role $role): JsonResponse {
        $role = $this->roleService->update(
            $role,
            $request->validated()
        );

        return response()->json([
            'message' => 'Role updated successfully',
            'role' => $role,
        ], 200);
    }

    public function destroy(Role $role): JsonResponse {
        $this->roleService->delete($role);

        return response()->json([
            'message' => 'Role deleted successfully',
        ], 200);
    }

    public function syncPermissions(SyncRolePermissionsRequest $request, Role $role): JsonResponse {
        $role = $this->roleService->syncPermissions(
            $role,
            $request->validated('permissions')
        );

        return response()->json([
            'message' => 'Role permissions synchronized successfully',
            'role' => $role,
        ], 200);
    }
}