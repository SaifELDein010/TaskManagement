<?php

namespace App\Activities\Task;

use App\Activitiess\Activity;
use App\Models\Task;

final class TaskCreated implements Activity {
    public function action(): string {
        return 'task.created';
    }

    public function metadata(): array {
        return [];
    }

    public function subjectType(): string {
        return Task::class;
    }
}