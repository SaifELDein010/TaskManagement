<?php

namespace App\Activities\Comment;

use App\Activitiess\Activity;
use App\Models\Comment;

final class CommentCreated implements Activity {
    public function __construct(
        private readonly bool $isReply,
        private readonly ?int $parentCommentId,
    ) {}

    public function action(): string {
        return 'comment.created';
    }

    public function metadata(): array {
        return [
            'is_reply' => $this->isReply,
            'parent_comment_id' => $this->parentCommentId,
        ];
    }

    public function subjectType(): string {
        return Comment::class;
    }
}