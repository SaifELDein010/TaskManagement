<?php

namespace App\Listeners;

use App\Events\TaskAssigned;
use App\Services\NotificationService;

class SendTaskAssignedNotification {
    public function __construct(private NotificationService $notificationService) {}
    public function handle(TaskAssigned $event): void {
        $this->notificationService->notifyTaskAssigned($event->task, $event->actorId);
    }
}