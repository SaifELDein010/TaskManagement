<?php

namespace App\Providers;

use App\Repositories\Attachment\AttachmentRepository;
use App\Repositories\Attachment\AttachmentRepositoryInterface;
use App\Repositories\Folder\FolderRepository;
use App\Repositories\Folder\FolderRepositoryInterface;
use App\Repositories\List\ListRepositoryInterface;
use App\Repositories\List\ListRepository;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\NotificationPreference\NotificationPreferenceRepository;
use App\Repositories\NotificationPreference\NotificationPreferenceRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Workspace\WorkspaceRepository;
use App\Repositories\Workspace\WorkspaceRepositoryInterface;
use App\Repositories\Task\TaskRepository;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Repositories\WorkspaceMember\WorkspaceMemberRepository;
use App\Repositories\WorkspaceMember\WorkspaceMemberRepositoryInterface;
use App\Repositories\ActivityLog\ActivityLogRepository;
use App\Repositories\ActivityLog\ActivityLogRepositoryInterface;
use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\StatusHistory\StatusHistoryRepository;
use App\Repositories\StatusHistory\StatusHistoryRepositoryInterface;
use App\Repositories\TaskRelationship\TaskRelationshipRepository;
use App\Repositories\TaskRelationship\TaskRelationshipRepositoryInterface;

use App\Events\AttachmentUploaded;
use App\Events\CommentCreated;
use App\Events\TaskAssigned;
use App\Events\TaskCreated;
use App\Events\TaskStatusChanged;

use App\Listeners\SendAttachmentUploadedNotification;
use App\Listeners\SendCommentCreatedNotification;
use App\Listeners\SendTaskAssignedNotification;
use App\Listeners\SendTaskCreatedNotification;
use App\Listeners\SendTaskStatusChangedNotification;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider {
    public function register(): void {

        \Illuminate\Foundation\Support\Providers\EventServiceProvider
        ::disableEventDiscovery();

        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(WorkspaceRepositoryInterface::class, WorkspaceRepository::class);
        $this->app->bind(WorkspaceMemberRepositoryInterface::class, WorkspaceMemberRepository::class);
        $this->app->bind(FolderRepositoryInterface::class, FolderRepository::class);
        $this->app->bind(AttachmentRepositoryInterface::class, AttachmentRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(NotificationPreferenceRepositoryInterface::class, NotificationPreferenceRepository::class);
        $this->app->bind(ListRepositoryInterface::class, ListRepository::class);
        $this->app->bind(ActivityLogRepositoryInterface::class, ActivityLogRepository::class);
        $this->app->bind(TaskRepositoryInterface::class, TaskRepository::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(StatusHistoryRepositoryInterface::class, StatusHistoryRepository::class);
        $this->app->bind(TaskRelationshipRepositoryInterface::class, TaskRelationshipRepository::class);
    }

    public function boot(): void {
        Event::listen(TaskCreated::class, SendTaskCreatedNotification::class);
        Event::listen(TaskAssigned::class, SendTaskAssignedNotification::class);
        Event::listen(TaskStatusChanged::class, SendTaskStatusChangedNotification::class);
        Event::listen(CommentCreated::class, SendCommentCreatedNotification::class);
        Event::listen(AttachmentUploaded::class, SendAttachmentUploadedNotification::class);
    }
}