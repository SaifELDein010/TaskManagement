<?php

namespace App\Activities\Task;

use App\Activities\ActivityInterface;
use App\Models\Task;

final class TaskDeleted implements ActivityInterface {
    public function action(): string {
        return 'task.deleted';
    }

    public function metadata(): array {
        return [];
    }

    public function subjectType(): string {
        return Task::class;
    }
}