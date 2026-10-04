<?php

namespace App\Services;

use App\Models\TaskList;
use App\Models\User;
use App\Repositories\List\ListRepositoryInterface;
use InvalidArgumentException;

class ListService {
    public function __construct(private ListRepositoryInterface $listRepository) {}

    public function create(User $user, array $data) {

        if ($data['folder_id'] !== null) {
            $folder = $this->listRepository->findFolder($data['folder_id']);

            if (!$folder) {
                throw new InvalidArgumentException('Folder not found.');
            }

            if ($folder->workspace_id != $data['workspace_id']) {
                throw new InvalidArgumentException(
                    'Folder must belong to the specified workspace.'
                );
            }
        }

        $data['created_by'] = $user->id;

        return $this->listRepository->create($data);
    }


    public function getAll(User $user){
        return $this->listRepository->getAllForUser($user);
    }

    public function getOne(User $user,int $id): ?TaskList {
        return $this->listRepository->findForUser($user, $id);
    }

    public function update(User $user, int $id, array $data): ?TaskList {
        $list = $this->listRepository->findForUser($user, $id);

        if (!$list) {
            return null;
        }

        return $this->listRepository->update($list, $data);
    }

    public function move(User $user, int $id, ?int $folderId): ?TaskList {
        $list = $this->listRepository->findForUser($user, $id);

        if (!$list) {
            return null;
        }

        if ($folderId === null) {
            return $this->listRepository->update(
                $list,
                [
                    'folder_id' => null,
                ]
            );
        }

        $folder = $this->listRepository->findFolder($folderId);

        if (!$folder) {
            throw new InvalidArgumentException(
                'Target folder not found.'
            );
        }

        if ($folder->workspace_id != $list->workspace_id) {
            throw new InvalidArgumentException(
                'List and target folder must belong to the same workspace.'
            );
        }


        return $this->listRepository->update(
            $list,
            [
                'folder_id' => $folderId,
            ]
        );
    }

    public function delete(User $user, int $id): ?bool {
        $list = $this->listRepository->findForUser($user, $id);

        if (!$list) {
            return null;
        }

        return $this->listRepository->delete($list);
    }

    public function restore(User $user, int $id): ?TaskList {
        $list = $this->listRepository->findForRestore($id);

        if (!$list) {
            return null;
        }

        return $this->listRepository->restore($list);
    }
}