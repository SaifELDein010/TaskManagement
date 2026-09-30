<?php

namespace App\Services;

use App\Models\Folder;
use App\Models\User;
use App\Repositories\Folder\FolderRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

class FolderService {
    public function __construct(private FolderRepositoryInterface $folderRepository) {}

    public function create(User $user, array $data): Folder {
        if (!empty($data['parent_id'])) {
            $parent = $this->folderRepository->find($data['parent_id']);

            if (!$parent) {
                throw new InvalidArgumentException('Parent folder not found.');
            }

            if ($parent->workspace_id !== $data['workspace_id']) {
                throw new InvalidArgumentException(
                    'Parent folder must belong to the same workspace.'
                );
            }

            $childrenCount = $this->folderRepository->countChildren($parent->id);

            if ($childrenCount >= 10) {
                throw new InvalidArgumentException(
                    'A folder cannot have more than 10 child folders.'
                );
            }
        }

        $data['created_by'] = $user->id;

        return $this->folderRepository->create($data);
    }

    public function getAllForUser(User $user){
        return $this->folderRepository->getAllForUser($user);
    }

    public function findForUser(User $user, int $id): ?Folder {
        return $this->folderRepository->findForUser($user, $id);
    }

    public function getAllForWorkspace(User $user, int $workspaceId) {
        return $this->folderRepository->getAllForWorkspace($user, $workspaceId);
    }

    public function getTree(User $user, int $workspaceId) {
        $folders = $this->folderRepository->getAllForWorkspace($user, $workspaceId);

        $tree = [];

        foreach ($folders as $folder) {
            if ($folder->parent_id === null) {
                $tree[$folder->id] = $folder->toArray();
                $tree[$folder->id]['children'] = [];
            }
        }

        foreach ($folders as $folder) {
            if ($folder->parent_id !== null) {
                $this->addToTree($tree, $folder);
            }
        }

        return array_values($tree);
    }

    public function update(User $user, int $id, array $data) {
        $folder = $this->folderRepository->findForUser($user, $id);

        if (!$folder) {
            return null;
        }

        return $this->folderRepository->update($folder, $data);
    }

    public function move(User $user, int $id, ?int $parentId): ?Folder {
        $folder = $this->folderRepository->findForUser($user, $id);

        if (!$folder) {
            return null;
        }

        if ($parentId === null) {
            return $this->folderRepository->update($folder, ['parent_id' => null]);
        }

        $parent = $this->folderRepository->find($parentId);

        if (!$parent) {
            throw new InvalidArgumentException('Parent folder not found.');
        }

        if ($parent->workspace_id !== $folder->workspace_id) {
            throw new InvalidArgumentException(
                'Parent folder must belong to the same workspace.'
            );
        }

        if ($parent->id === $folder->id) {
            throw new InvalidArgumentException(
                'A folder cannot be its own parent.'
            );
        }

        if ($this->wouldCreateCycle($folder, $parent)) {
            throw new InvalidArgumentException(
                'Cannot move a folder inside one of its descendants.'
            );
        }

        $childrenCount = $this->folderRepository->countChildren($parent->id);

        if ($childrenCount >= 10) {
            throw new InvalidArgumentException(
                'A folder cannot have more than 10 child folders.'
            );
        }

        return $this->folderRepository->update(
            $folder,
            [
                'parent_id' => $parentId,
            ]
        );
    }

    public function delete(User $user, int $id): bool {
        $folder = $this->folderRepository->findForUser($user, $id);

        if (!$folder) {
            return false;
        }

        if ($this->folderRepository->countChildren($folder->id) > 0) {
            throw new InvalidArgumentException(
                'Cannot delete a folder that contains child folders.'
            );
        }

        return $this->folderRepository->delete($folder);
    }

    private function wouldCreateCycle(Folder $folder, Folder $newParent): bool {
        $current = $newParent;

        while ($current->parent_id !== null) {
            if ($current->parent_id === $folder->id) {
                return true;
            }

            $current = $this->folderRepository->find(
                $current->parent_id
            );

            if (!$current) {
                break;
            }
        }

        return $current?->id === $folder->id;
    }

    private function addToTree(array &$tree, Folder $folder ): void {
        foreach ($tree as &$parent) {
            if ($parent['id'] === $folder->parent_id) {

                $parent['children'][] = array_merge(
                    $folder->toArray(),
                    [
                        'children' => [],
                    ]
                );

                return;
            }

            if (!empty($parent['children'])) {
                $this->addToTree($parent['children'], $folder);
            }
        }
        
    }
}