<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\Comment;
use App\Models\Task;
use App\Models\User;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\NotificationPreference\NotificationPreferenceRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\log;

class NotificationService {
    public const EVENT_TYPES = [
        'task.created',
        'task.assigned',
        'task.status_changed',
        'comment.created',
        'attachment.uploaded',
    ];

    public function __construct(
        private NotificationRepositoryInterface $notificationRepository,
        private NotificationPreferenceRepositoryInterface $preferenceRepository,
    ) {}

    public function getForUser(User $user, ?bool $read = null, ?string $eventType = null): Collection {
        return $this->notificationRepository->getForUser($user->id, $read, $eventType);
    }

    public function getPreferences(User $user): Collection {
        $stored = $this->preferenceRepository->getForUser($user->id)->keyBy('event_type');

        return collect(self::EVENT_TYPES)->map(function (string $eventType) use ($stored) {
            $preference = $stored->get($eventType);

            return [
                'event_type' => $eventType,
                'in_app' => $preference?->in_app ?? true,
                'email' => $preference?->email ?? true,
            ];
        })->values();
    }

    public function updatePreferences(User $user, array $preferences): Collection {
        foreach ($preferences as $preference) {
            $this->preferenceRepository->updateOrCreate($user->id, $preference['event_type'], [
                'in_app' => $preference['in_app'],
                'email' => $preference['email'],
            ]);
        }

        return $this->getPreferences($user);
    }

    public function notifyTaskCreated(Task $task, int $actorId): void {
        $memberIds = $this->getWorkspaceMemberIds($task);

        $this->deliverToUserIds($memberIds, $actorId, 'task.created', 'New task assigned', "You were assigned to the task \"{$task->title}\".", $this->taskData($task),);
    }

    public function notifyTaskAssigned(Task $task, int $actorId): void {
        $this->deliverToUserIds([$task->assigned_to], $actorId, 'task.assigned', 'Task assigned to you', "You were assigned to the task \"{$task->title}\".", $this->taskData($task),);
    }

    public function notifyTaskStatusChanged(Task $task, int $actorId, string $oldStatus, string $newStatus): void {
    
        $memberIds = $this->getWorkspaceMemberIds($task);
    
        $this->deliverToTaskUsers($memberIds, $actorId, 'task.status_changed', 'Task status changed', "Task \"{$task->title}\" changed from {$oldStatus} to {$newStatus}.", array_merge($this->taskData($task), ['old_status' => $oldStatus, 'new_status' => $newStatus]),);
    }

    public function notifyCommentCreated(Comment $comment, Task $task, int $actorId): void {
        $memberIds = $this->getWorkspaceMemberIds($task);  

        $this->deliverToTaskUsers($memberIds, $actorId, 'comment.created', 'New task comment', "A new comment was added to \"{$task->title}\".", array_merge($this->taskData($task), ['comment_id' => $comment->id]),);
    }

    public function notifyAttachmentUploaded(Attachment $attachment, Task $task, int $actorId): void {
         $memberIds = $this->getWorkspaceMemberIds($task);
    
        $this->deliverToTaskUsers($memberIds, $actorId, 'attachment.uploaded', 'New task attachment', "A file was attached to \"{$task->title}\".", array_merge($this->taskData($task), ['attachment_id' => $attachment->id]),);
    }

    private function deliverToTaskUsers(array $userIds, int $actorId, string $eventType, string $title, string $body, array $data): void {
        $this->deliverToUserIds($userIds, $actorId, $eventType, $title, $body, $data);
    }

    private function deliverToUserIds(array $userIds, int $actorId, string $eventType, string $title, string $body, array $data): void {
        $recipientIds = collect($userIds)
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->reject(fn ($id) => $id === $actorId)
            ->values();

        User::query()->whereIn('id', $recipientIds)
            ->each(function (User $recipient) use ($eventType, $title, $body, $data) {
                $preference = $this->preferenceRepository->findForUserEvent($recipient->id, $eventType);

                $inAppEnabled = $preference?->in_app ?? true;

                if ($inAppEnabled) {
                    $this->notificationRepository->create([
                        'user_id' => $recipient->id,
                        'event_type' => $eventType,
                        'title' => $title,
                        'body' => $body,
                        'data' => $data,
                    ]);
                }
        });
    }

    private function taskData(Task $task): array {
        return ['task_id' => $task->id, 'list_id' => $task->list_id];
    }

    private function getWorkspaceMemberIds(Task $task): array {
        $task->loadMissing('list.folder.workspace.members');

        return $task->list
            ->folder
            ->workspace
            ->members()
            ->pluck('workspace_members.user_id')
            ->all();
    }
}