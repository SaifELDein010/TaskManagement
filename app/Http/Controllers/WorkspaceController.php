<?php

namespace App\Http\Controllers;

use App\Http\Requests\Workspace\StoreWorkspaceRequest;
use App\Services\WorkspaceService;
use Illuminate\Http\JsonResponse;

class WorkspaceController extends Controller {
    public function __construct(
        private WorkspaceService $workspaceService
    ) {
    }

    public function store(StoreWorkspaceRequest $request): JsonResponse {
        $workspace = $this->workspaceService->create($request->validated());

        return response()->json([
            'message' => 'Workspace created successfully.',
            'data' => $workspace,
        ], 201);
    }
}