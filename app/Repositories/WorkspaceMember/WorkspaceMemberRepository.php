<?php

namespace App\Repositories\WorkspaceMember;

use App\Models\WorkspaceMember;

class WorkspaceMemberRepository implements WorkspaceMemberRepositoryInterface
{
    public function find(int $workspaceId, int $userId): ?WorkspaceMember {
        return WorkspaceMember::query()
            ->where('workspace_id', $workspaceId)
            ->where('user_id', $userId)
            ->first();
    }

    public function create(array $data) {
        return WorkspaceMember::create($data);
    }

    public function update(WorkspaceMember $workspaceMember, array $data) {
        $workspaceMember->update($data);

        return $workspaceMember->refresh();
    }

    public function delete(WorkspaceMember $workspaceMember) {
        $workspaceMember->delete();
    }
}