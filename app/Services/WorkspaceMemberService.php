<?php

namespace App\Services;

use App\Models\WorkspaceMember;
use App\Repositories\WorkspaceMember\WorkspaceMemberRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WorkspaceMemberService {
    public function __construct(private WorkspaceMemberRepositoryInterface $workspaceMemberRepository) {}

    public function get(int $workspaceId, int $userId) {
        $workspaceMember = $this->workspaceMemberRepository->find($workspaceId, $userId);

        if (!$workspaceMember) {
            throw new ModelNotFoundException('Workspace member not found.');
        }

        return $workspaceMember;
    }

    public function create(int $workspaceId, int $userId) {
        $existingMember = $this->workspaceMemberRepository->find($workspaceId, $userId);

        if ($existingMember) {
            throw new \DomainException('User is already a member of this workspace.');
        }

        return $this->workspaceMemberRepository->create([
            'workspace_id' => $workspaceId,
            'user_id' => $userId,
            'is_owner' => false,
        ]);
    }

    public function update(int $workspaceId, int $userId, array $data) {
        $workspaceMember = $this->get($workspaceId, $userId);

        return $this->workspaceMemberRepository->update($workspaceMember, $data);
    }

    public function delete(int $workspaceId, int $userId) {
        $workspaceMember = $this->get($workspaceId, $userId);

        $this->workspaceMemberRepository->delete($workspaceMember);
    }
}