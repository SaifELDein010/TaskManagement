<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Services\NotificationService;
// use Illuminate\Support\Facades\Log;
class SendTaskCreatedNotification {
    public function __construct(private NotificationService $notificationService) {}
    public function handle(TaskCreated $event): void {
        $this->notificationService->notifyTaskCreated($event->task, $event->actorId);
    }
}