<?php

namespace App\Services;

use App\Models\Comment;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Task\TaskRepositoryInterface;
use Illuminate\Support\Collection;
use InvalidArgumentException;
use App\Services\ActivityLogService;
use Illuminate\Support\Facades\DB;
use App\Activities\Comment\CommentCreated;

class CommentService {
    public function __construct(
        private CommentRepositoryInterface $commentRepository,
        private TaskRepositoryInterface $taskRepository,
        private ActivityLogService $activityLogService
    ) {}

    public function create(int $taskId, int $userId, array $data): Comment {
        return DB::transaction(function () use ($taskId, $userId, $data) {
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

            $comment = $this->commentRepository->create([
                'task_id' => $taskId,
                'user_id' => $userId,
                'parent_comment_id' => $parentCommentId,
                'content' => $data['content'],
            ]);

            $task = $this->taskRepository->find($taskId);

            if (!$task) {
                throw new InvalidArgumentException(
                    'Task not found.'
                );
            }

            $workspaceId = $task->list->workspace_id;

            $this->activityLogService->create(
                workspaceId: $workspaceId,
                actorId: $userId,
                activity: new CommentCreated(isReply: $parentCommentId !== null, parentCommentId: $parentCommentId,),
                subjectId: $comment->id,
            );

            return $comment;
        });
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