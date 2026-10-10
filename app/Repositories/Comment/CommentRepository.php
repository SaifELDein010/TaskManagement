<?php

namespace App\Repositories;

use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Support\Collection;

class CommentRepository implements CommentRepositoryInterface {
    public function create(array $data): Comment {
        return Comment::create($data);
    }

    public function findById(int $id): ?Comment {
        return Comment::find($id);
    }

    public function getByTaskId(int $taskId): Collection {
        return Comment::query()
            ->where('task_id', $taskId)
            ->with('user')
            ->orderBy('created_at')
            ->get();
    }

    public function update(Comment $comment, array $data): Comment {
        $comment->update($data);

        return $comment->fresh();
    }

    public function delete(Comment $comment): bool {
        return $comment->delete();
    }
}