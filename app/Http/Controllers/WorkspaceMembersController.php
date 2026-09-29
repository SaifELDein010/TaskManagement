<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkspaceMember\StoreWorkspaceMemberRequest;
use App\Http\Requests\WorkspaceMember\UpdateWorkspaceMemberRequest;
use App\Services\WorkspaceMemberService;
use Illuminate\Http\JsonResponse;

class WorkspaceMembersController extends Controller
{
    public function __construct(
        private WorkspaceMemberService $workspaceMemberService
    ) {
    }

    public function show(int $id, int $userId) {
        $workspaceMember = $this->workspaceMemberService->get(
            $id,
            $userId
        );

        return response()->json([
            'data' => $workspaceMember,
        ]);
    }

    public function store(StoreWorkspaceMemberRequest $request, int $id, int $userId) {
        $workspaceMember = $this->workspaceMemberService->create(
            $id,
            $userId
        );

        return response()->json([
            'message' => 'User added to workspace successfully.',
            'data' => $workspaceMember,
        ], 201);
    }

    public function update(UpdateWorkspaceMemberRequest $request, int $id, int $userId) {
        $workspaceMember = $this->workspaceMemberService->update(
            $id,
            $userId,
            $request->validated()
        );

        return response()->json([
            'message' => 'Workspace member updated successfully.',
            'data' => $workspaceMember,
        ]);
    }

    public function destroy( int $id, int $userId) {
        $this->workspaceMemberService->delete(
            $id,
            $userId
        );

        return response()->json([
            'message' => 'User removed from workspace successfully.',
        ]);
    }
}