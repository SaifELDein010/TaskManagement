<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskAssigned implements ShouldDispatchAfterCommit {
    use Dispatchable, SerializesModels;
    public function __construct(public Task $task, public int $actorId, public ?int $oldAssigneeId) {}
}