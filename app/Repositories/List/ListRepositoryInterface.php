<?php

namespace App\Repositories\List;

use App\Models\TaskList;
use App\Models\User;

interface ListRepositoryInterface {
    public function create(array $data);
    public function find(int $id): ?TaskList;
    public function findForUser(User $user, int $id): ?TaskList;
    public function getAllForUser(User $user);
    public function update(TaskList $list, array $data);
    public function delete(TaskList $list);
    public function findFolder(int $folderId);
    public function findForRestore(int $id): ?TaskList;
    public function restore(TaskList $list);
}