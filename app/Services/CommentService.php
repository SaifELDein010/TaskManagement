<?php

namespace App\Services;

use App\Models\Comment;
use App\Repositories\Contracts\CommentRepositoryInterface;
use Illuminate\Support\Collection;
use InvalidArgumentException;

class CommentService {
    public function __construct(private CommentRepositoryInterface $commentRepository) {}

    public function create(int $taskId, int $userId, array $data): Comment {
        $parentCommentId = $data['parent_comment_id'] ?? null;

        if ($parentCommentId !== null) {
            $parentComment = $this->commentRepository->findById($parentCommentId);

            if (!$parentComment) {
                throw new InvalidArgumentException(
                    'Parent comment not found.'
                );
            }

            if ($parentComment->task_id !== $taskId) {
                throw new InvalidArgumentException(
                    'Parent comment must belong to the same task.'
                );
            }
        }

        return $this->commentRepository->create([
            'task_id' => $taskId,
            'user_id' => $userId,
            'parent_comment_id' => $parentCommentId,
            'content' => $data['content'],
        ]);
    }

    public function getByTask(int $taskId): Collection {
        $comments = $this->commentRepository->getByTaskId($taskId);

        return $this->buildThreadTree($comments);
    }

    public function update(Comment $comment, array $data): Comment {
        return $this->commentRepository->update($comment, ['content' => $data['content'],]);
    }

    public function delete(Comment $comment): bool {
        return $this->commentRepository->delete($comment);
    }

    private function buildThreadTree(Collection $comments): Collection {
        $grouped = $comments->groupBy('parent_comment_id');

        return $this->buildReplies($grouped, null);
    }

    private function buildReplies(Collection $grouped, ?int $parentId ): Collection {
        return collect($grouped->get($parentId, []))
            ->map(function (Comment $comment) use ($grouped) {
                $comment->setRelation('replies', $this->buildReplies($grouped, $comment->id));

                return $comment;
            })
            ->values();
    }
}