<?php

namespace App\Events;

use App\Models\Attachment;
use App\Models\Task;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttachmentUploaded implements ShouldDispatchAfterCommit {
    use Dispatchable, SerializesModels;
    public function __construct(public Attachment $attachment, public Task $task, public int $actorId) {}
}