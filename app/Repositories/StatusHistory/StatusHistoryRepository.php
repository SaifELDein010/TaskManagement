<?php

namespace App\Repositories\StatusHistory;

use App\Models\TaskStatusHistory;

class StatusHistoryRepository implements StatusHistoryRepositoryInterface {
    public function create(array $data): TaskStatusHistory {
        return TaskStatusHistory::create($data);
    }
}