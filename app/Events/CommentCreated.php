<?php

namespace App\Events;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentCreated implements ShouldDispatchAfterCommit {
    use Dispatchable, SerializesModels;
    public function __construct(public Comment $comment, public Task $task, public int $actorId) {}
}