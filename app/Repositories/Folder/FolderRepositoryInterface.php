<?php

namespace App\Repositories\Folder;

use App\Models\Folder;
use App\Models\User;

interface FolderRepositoryInterface {
    public function create(array $data);

    public function find(int $id): ?Folder;

    public function findForUser(User $user, int $id): ?Folder;

    public function getAllForUser(User $user);

    public function getAllForWorkspace(User $user, int $workspaceId);

    public function update(Folder $folder, array $data);

    public function delete(Folder $folder): bool;

    public function countChildren(int $folderId): int;

    public function hasChild(int $folderId, int $childId): bool;
}