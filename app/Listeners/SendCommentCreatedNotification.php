<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Services\NotificationService;

class SendCommentCreatedNotification {
    public function __construct(private NotificationService $notificationService) {}
    public function handle(CommentCreated $event): void {
        $this->notificationService->notifyCommentCreated($event->comment, $event->task, $event->actorId);
    }
}