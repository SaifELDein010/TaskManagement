<?php

namespace App\Repositories\WorkspaceMember;

use App\Models\WorkspaceMember;

interface WorkspaceMemberRepositoryInterface {
    public function find(int $workspaceId, int $userId): ?WorkspaceMember;

    public function create(array $data);

    public function update(WorkspaceMember $workspaceMember, array $data);

    public function delete(WorkspaceMember $workspaceMember);
}