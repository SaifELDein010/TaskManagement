<?php

namespace App\Http\Controllers;

use App\Http\Requests\WorkspaceMember\StoreWorkspaceMemberRequest;
use App\Http\Requests\WorkspaceMember\UpdateWorkspaceMemberRequest;
use App\Services\WorkspaceMemberService;
use Illuminate\Support\Facades\Auth;


class WorkspaceMembersController extends Controller {
    public function __construct(private WorkspaceMemberService $workspaceMemberService) {}

    public function show(int $id, int $userId) {
        $workspaceMember = $this->workspaceMemberService->get($id, $userId);

        return response()->json([
            'data' => $workspaceMember,
        ]);
    }

    public function store(StoreWorkspaceMemberRequest $request, int $id, int $userId) {
        $actor = Auth::guard('api')->user();

        $workspaceMember = $this->workspaceMemberService->create($id, $userId, $actor->id);

        return response()->json([
            'message' => 'User added to workspace successfully.',
            'data' => $workspaceMember,
        ], 201);
    }

    public function update(UpdateWorkspaceMemberRequest $request, int $id, int $userId) {
        $actor = Auth::guard('api')->user();

        $workspaceMember = $this->workspaceMemberService->update($id, $userId, $request->validated(), $actor->id);

        return response()->json([
            'message' => 'Workspace member updated successfully.',
            'data' => $workspaceMember,
        ]);

    }

    public function destroy(int $id, int $userId) {
        $actor = Auth::guard('api')->user();

        $this->workspaceMemberService->delete($id, $userId, $actor->id);

        return response()->json([
            'message' => 'User removed from workspace successfully.',
        ]);
    }
}