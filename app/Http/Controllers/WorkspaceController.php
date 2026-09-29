<?php

namespace App\Http\Controllers;

use App\Http\Requests\Workspace\StoreWorkspaceRequest;
use App\Http\Requests\Workspace\UpdateWorkspaceRequest;
use App\Services\WorkspaceService;
use Illuminate\Support\Facades\Auth;

class WorkspaceController extends Controller
{
    public function __construct(
        private WorkspaceService $workspaceService
    ) {
    }

    public function store(StoreWorkspaceRequest $request) {
        $user = Auth::guard('api')->user();

        $workspace = $this->workspaceService->create(
            $user,
            $request->validated()
        );

        return response()->json([
            'message' => 'Workspace created successfully.',
            'data' => $workspace,
        ], 201);
    }

    public function index(){
        $user = Auth::guard('api')->user();

        $workspaces = $this->workspaceService->list($user);

        return response()->json([
            'message' => 'sending access Workspaces successfully.',
            'data' => $workspaces,
        ]);
    }

    public function show(int $id) {
        $user = Auth::guard('api')->user();

        $workspace = $this->workspaceService->view(
            $user,
            $id
        );

        if (!$workspace) {
            return response()->json([
                'message' => 'Workspace not found or user does not have access.',
            ], 404);
        }

        return response()->json([
            'message' => 'sending access Workspace successfully.',
            'data' => $workspace,
        ]);
    }

    public function update(UpdateWorkspaceRequest $request, int $id){
        $user = Auth::guard('api')->user();

        $workspace = $this->workspaceService->view(
            $user,
            $id
        );

        if (!$workspace) {
            return response()->json([
                'message' => 'Workspace not found or user does not have access.',
            ], 404);
        }

        $workspace = $this->workspaceService->update(
            $user,
            $workspace,
            $request->validated()
        );

        if (!$workspace) {
            return response()->json([
                'message' => 'User is not allowed to update this workspace.',
            ], 403);
        }

        return response()->json([
            'message' => 'Workspace updated successfully.',
            'data' => $workspace,
        ]);
    }

    public function destroy(int $id) {
        $user = Auth::guard('api')->user();

        $workspace = $this->workspaceService->view(
            $user,
            $id
        );

        if (!$workspace) {
            return response()->json([
                'message' => 'Workspace not found or user does not have access.',
            ], 404);
        }

        $deleted = $this->workspaceService->delete(
            $user,
            $workspace
        );

        if (!$deleted) {
            return response()->json([
                'message' => 'User is not allowed to delete this workspace.',
            ], 403);
        }

        return response()->json([
            'message' => 'Workspace deleted successfully.',
        ]);
    }
}
