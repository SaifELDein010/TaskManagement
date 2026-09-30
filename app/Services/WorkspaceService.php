<?php

namespace App\Services;

use App\Models\Workspace;
use App\Models\User;
use App\Repositories\Workspace\WorkspaceRepositoryInterface;
use App\Repositories\WorkspaceMember\WorkspaceMemberRepositoryInterface;
use Illuminate\Support\Facades\DB;

class WorkspaceService {
    public function __construct(
        private WorkspaceRepositoryInterface $workspaceRepository,
        private WorkspaceMemberRepositoryInterface $workspaceMemberRepository
    ) {}

    public function create(User $user, array $data): Workspace {
        
        return DB::transaction(function () use ($user, $data) {
            $workspace = $this->workspaceRepository->create($data);

            $this->workspaceMemberRepository->create([
                'workspace_id' => $workspace->id,
                'user_id' => $user->id,
                'is_owner' => true,
            ]);

            return $workspace;
        });
    }

    public function list(User $user) {
        return $this->workspaceRepository->list($user);
    }

    public function view(User $user, int $id) {
        return $this->workspaceRepository->view($user, $id);
    }

    public function update(User $user, Workspace $workspace, array $data) {
        return $this->workspaceRepository->update($user, $workspace, $data);
    }

    public function delete(User $user, Workspace $workspace):bool {
        return $this->workspaceRepository->delete($user, $workspace);
    }

    public function restore(User $user, int $id): ?Workspace {
        $workspace = $this->workspaceRepository->findForRestore($id);

        if (!$workspace) {
            return null;
        }

        return $this->workspaceRepository->restore(
            $user,
            $workspace
        );
    }
}