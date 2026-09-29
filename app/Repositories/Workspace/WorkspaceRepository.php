<?php

namespace App\Repositories\Workspace;

use App\Models\Workspace;
use App\Models\User;

class WorkspaceRepository implements WorkspaceRepositoryInterface {

    private function isOwner(User $user, Workspace $workspace) {

        return Workspace::query()
            ->join(
                'workspace_members',
                'workspace_members.workspace_id',
                '=',
                'workspaces.id'
            )
            ->where(
                'workspaces.id',
                $workspace->id
            )
            ->where(
                'workspace_members.user_id',
                $user->id
            )
            ->where(
                'workspace_members.is_owner',
                true
            )
            ->exists();

    }
    public function create(array $data) {
        return Workspace::create($data); 
    }

    public function list(User $user) {
        return Workspace::query()
            ->join(
                'workspace_members',
                'workspace_members.workspace_id',
                '=',
                'workspaces.id'
            )
            ->where(
                'workspace_members.user_id',
                $user->id
            )
            ->select('workspaces.*')
            ->get();
    }
    public function view(User $user, int $id) {
        return Workspace::query()
            ->join(
                'workspace_members',
                'workspace_members.workspace_id',
                '=',
                'workspaces.id'
            )
            ->where(
                'workspace_members.user_id',
                $user->id
            )
            ->Where(
                'workspaces.id',
                $id
            )
            ->select('workspaces.*')
            ->first();
    }
    public function update(User $user, Workspace $workspace, array $data): ?Workspace {

        if(!$this->isOwner($user, $workspace)){
            return null;
        }

        $workspace->update($data);

        return $workspace->refresh();

    }
    public function delete(User $user, Workspace $workspace) {

        if(!$this->isOwner($user, $workspace)){
            return false;
        }

        $workspace->delete();
        return true;
    }

    public function findForRestore(int $id): ?Workspace {
        return Workspace::withTrashed()
            ->where('id', $id)
            ->first();
    }

    public function restore(User $user,Workspace $workspace): ?Workspace {
        if (!$this->isOwner($user, $workspace)) {
            return null;
        }

        $workspace->restore();

        return $workspace->refresh();
    }

}