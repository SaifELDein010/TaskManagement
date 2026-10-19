<?php

namespace App\Repositories\NotificationPreference;

use App\Models\NotificationPreference;
use Illuminate\Support\Collection;

interface NotificationPreferenceRepositoryInterface {
    public function findForUserEvent(int $userId, string $eventType): ?NotificationPreference;
    public function getForUser(int $userId): Collection;
    public function updateOrCreate(int $userId, string $eventType, array $channels): NotificationPreference;
}