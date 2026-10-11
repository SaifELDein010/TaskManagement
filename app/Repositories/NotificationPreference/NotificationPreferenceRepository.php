<?php

namespace App\Repositories\NotificationPreference;

use App\Models\NotificationPreference;
use Illuminate\Support\Collection;

class NotificationPreferenceRepository implements NotificationPreferenceRepositoryInterface {
    public function findForUserEvent(int $userId, string $eventType): ?NotificationPreference {
        return NotificationPreference::query()->where('user_id', $userId)->where('event_type', $eventType)->first();
    }

    public function getForUser(int $userId): Collection {
        return NotificationPreference::query()->where('user_id', $userId)->orderBy('event_type')->get();
    }

    public function updateOrCreate(int $userId, string $eventType, array $channels): NotificationPreference {
        return NotificationPreference::updateOrCreate(['user_id' => $userId, 'event_type' => $eventType], $channels);
    }
}