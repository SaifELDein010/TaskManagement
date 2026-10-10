<?php

namespace App\Activities\Task;

use App\Activitiess\Activity;
use App\Models\Task;

final class TaskPriorityChanged implements Activity {
    public function __construct(
        private readonly string $oldPriority,
        private readonly string $newPriority,
    ) {}

    public function action(): string {
        return 'task.priority_changed';
    }

    public function metadata(): array {
        return [
            'old_priority' => $this->oldPriority,
            'new_priority' => $this->newPriority,
        ];
    }

    public function subjectType(): string {
        return Task::class;
    }
}