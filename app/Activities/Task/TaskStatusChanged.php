<?php

namespace App\Activities\Task;

use App\Activities\ActivityInterface;
use App\Models\Task;

final class TaskStatusChanged implements ActivityInterface {
    public function __construct(
        private readonly string $oldStatus,
        private readonly string $newStatus,
    ) {}

    public function action(): string {
        return 'task.status_changed';
    }

    public function metadata(): array {
        return [
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
        ];
    }

    public function subjectType(): string {
        return Task::class;
    }
}