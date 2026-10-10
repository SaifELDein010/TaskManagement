<?php

namespace App\Repositories\StatusHistory;

use App\Models\TaskStatusHistory;

interface StatusHistoryRepositoryInterface{
    public function create(array $data): TaskStatusHistory;
}