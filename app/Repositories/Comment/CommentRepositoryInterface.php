<?php

namespace App\Repositories\Contracts;

use App\Models\Comment;
use Illuminate\Support\Collection;

interface CommentRepositoryInterface {
    public function create(array $data): Comment;
    public function findById(int $id): ?Comment;
    public function getByTaskId(int $taskId): Collection;
    public function update(Comment $comment, array $data): Comment;
    public function delete(Comment $comment): bool;
}