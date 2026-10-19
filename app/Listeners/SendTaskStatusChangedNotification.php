<?php

namespace App\Listeners;

use App\Events\TaskStatusChanged;
use App\Services\NotificationService;

class SendTaskStatusChangedNotification {
    public function __construct(private NotificationService $notificationService) {}
    public function handle(TaskStatusChanged $event): void {
        $this->notificationService->notifyTaskStatusChanged($event->task, $event->actorId, $event->oldStatus, $event->newStatus);
    }
}