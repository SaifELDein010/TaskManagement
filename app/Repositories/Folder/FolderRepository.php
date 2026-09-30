<?php

namespace App\Repositories\Folder;

use App\Models\Folder;
use App\Models\User;

class FolderRepository implements FolderRepositoryInterface {
    public function create(array $data) {
        return Folder::create($data);
    } 

    public function find(int $id): ?Folder{
        return Folder::query()
            ->where('id', $id)
            ->first();
    }

    public function findForUser(User $user, int $id): ?Folder {
        return Folder::query()
            ->where('folders.id', $id)
            ->whereHas('workspace.members', function ($query) use ($user) {$query->where('user_id', $user->id);})
            ->first();
    }

    public function getAllForUser(User $user){
        return Folder::query()
            ->whereHas('workspace.members', function ($query) use ($user) {$query->where('user_id', $user->id);})
            ->get();
    }

    public function getAllForWorkspace(User $user, int $workspaceId){
        return Folder::query()
            ->where('workspace_id', $workspaceId)
            ->whereHas('workspace.members', function ($query) use ($user) {$query->where('user_id', $user->id);})
            ->orderBy('parent_id')
            ->orderBy('id')
            ->get();
    }

    public function update(Folder $folder, array $data){
        $folder->update($data);

        return $folder->refresh();
    }

    public function delete(Folder $folder): bool {
        return $folder->delete();
    }

    public function countChildren(int $folderId): int {
        return Folder::query()
            ->where('parent_id', $folderId)
            ->count();
    }

    public function hasChild(int $folderId, int $childId): bool {
        return Folder::query()
            ->where('parent_id', $folderId)
            ->where('id', $childId)
            ->exists();
    }
}