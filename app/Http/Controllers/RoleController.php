<?php

namespace App\Http\Controllers;

use App\Http\Requests\Role\CreateRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Requests\Role\SyncRolePermissionsRequest;
use App\Services\Role\RoleService;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;
use DomainException;

class RoleController extends Controller
{
    public function __construct(
        private readonly RoleService $roleService
    ) {
    }

    public function index(): JsonResponse {
        $roles = $this->roleService->getRoles();

        return response()->json([
            'message' => 'Roles retrieved successfully.',
            'data' => $roles,
        ]);
    }

    public function show(Role $role): JsonResponse {
        try {
            $role = $this->roleService->getRole($role);

            return response()->json([
                'message' => 'Role retrieved successfully.',
                'data' => $role,
            ]);
        } catch (DomainException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'data' => null,
            ], 404);
        }
    }

    public function store(CreateRoleRequest $request): JsonResponse {
        $role = $this->roleService->createRole($request->validated());

        return response()->json([
            'message' => 'Role created successfully.',
            'data' => $role,
        ], 201);
    }

    public function update(
        UpdateRoleRequest $request,
        Role $role
    ): JsonResponse {
        try {
            $role = $this->roleService->updateRole($role, $request->validated());

            return response()->json([
                'message' => 'Role updated successfully.',
                'data' => $role,
            ]);
        } catch (DomainException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'data' => null,
            ], 404);
        }
    }

    public function destroy(Role $role): JsonResponse {
        try {
            $this->roleService->deleteRole($role);

            return response()->json([
                'message' => 'Role deleted successfully.',
                'data' => null,
            ]);
        } catch (DomainException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'data' => null,
            ], 409);
        }
    }

    public function syncPermissions(
        SyncRolePermissionsRequest $request,
        Role $role
    ): JsonResponse {
        try {
            $role = $this->roleService->syncPermissions($role,$request->validated('permissions'));

            return response()->json([
                'message' => 'Role permissions synchronized successfully.',
                'data' => $role,
            ]);
        } catch (DomainException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
                'data' => null,
            ], 404);
        }
    }
}