<?php

namespace App\Providers;

use App\Repositories\AttachmentRepository;
use App\Repositories\Attachment\AttachmentRepositoryInterface;
use App\Repositories\Folder\FolderRepository;
use App\Repositories\Folder\FolderRepositoryInterface;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Notification\NotificationRepositoryInterface;
use App\Repositories\NotificationPreference\NotificationPreferenceRepository;
use App\Repositories\NotificationPreference\NotificationPreferenceRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Workspace\WorkspaceRepository;
use App\Repositories\Workspace\WorkspaceRepositoryInterface;
use App\Repositories\WorkspaceMember\WorkspaceMemberRepository;
use App\Repositories\WorkspaceMember\WorkspaceMemberRepositoryInterface;
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
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(WorkspaceRepositoryInterface::class, WorkspaceRepository::class);
        $this->app->bind(WorkspaceMemberRepositoryInterface::class, WorkspaceMemberRepository::class);
        $this->app->bind(FolderRepositoryInterface::class, FolderRepository::class);
        $this->app->bind(AttachmentRepositoryInterface::class, AttachmentRepository::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(NotificationPreferenceRepositoryInterface::class, NotificationPreferenceRepository::class);
    }

    public function boot(): void {
        Event::listen(TaskCreated::class, SendTaskCreatedNotification::class);
        Event::listen(TaskAssigned::class, SendTaskAssignedNotification::class);
        Event::listen(TaskStatusChanged::class, SendTaskStatusChangedNotification::class);
        Event::listen(CommentCreated::class, SendCommentCreatedNotification::class);
        Event::listen(AttachmentUploaded::class, SendAttachmentUploadedNotification::class);
    }
}