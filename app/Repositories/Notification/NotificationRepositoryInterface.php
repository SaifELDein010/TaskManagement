<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use Illuminate\Support\Collection;

interface NotificationRepositoryInterface {
    public function create(array $data): Notification;
    public function getForUser(int $userId, ?bool $read = null, ?string $eventType = null): Collection;
}