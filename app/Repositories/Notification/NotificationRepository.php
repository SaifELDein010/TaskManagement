<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use Illuminate\Support\Collection;

class NotificationRepository implements NotificationRepositoryInterface {
    public function create(array $data): Notification {
        return Notification::create($data);
    }

    public function getForUser(int $userId, ?bool $read = null, ?string $eventType = null): Collection {
        return Notification::query()
            ->where('user_id', $userId)
            ->when($read !== null, fn ($query) => $read ? $query->whereNotNull('read_at') : $query->whereNull('read_at'))
            ->when($eventType !== null, fn ($query) => $query->where('event_type', $eventType))
            ->latest()
            ->get();
    }
}