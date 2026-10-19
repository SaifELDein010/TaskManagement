<?php

namespace App\Listeners;

use App\Events\AttachmentUploaded;
use App\Services\NotificationService;

class SendAttachmentUploadedNotification {
    public function __construct(private NotificationService $notificationService) {}
    public function handle(AttachmentUploaded $event): void {
        $this->notificationService->notifyAttachmentUploaded($event->attachment, $event->task, $event->actorId);
    }
}