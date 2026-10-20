<?php

namespace App\Activities\Task;

use App\Activities\ActivityInterface;
use App\Models\Task;

final class TaskAssigned implements ActivityInterface {
    public function __construct(
        private readonly ?int $oldAssigneeId,
        private readonly ?int $newAssigneeId,
    ) {}

    public function action(): string {
        return 'task.assigned';
    }

    public function metadata(): array {
        return [
            'old_assignee_id' => $this->oldAssigneeId,
            'new_assignee_id' => $this->newAssigneeId,
        ];
    }

    public function subjectType(): string {
        return Task::class;
    }
}