<?php

namespace App\Services;

use App\Repositories\Workspace\WorkspaceRepositoryInterface;
use App\Repositories\Folder\FolderRepositoryInterface;
use App\Repositories\List\ListRepositoryInterface;
use App\Repositories\Task\TaskRepositoryInterface;
use App\Repositories\Comment\CommentRepositoryInterface;
use App\Repositories\Attachment\AttachmentRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorizationResourceResolver
{
    public function __construct(
        private readonly WorkspaceRepositoryInterface $workspaces,
        private readonly FolderRepositoryInterface $folders,
        private readonly ListRepositoryInterface $lists,
        private readonly TaskRepositoryInterface $tasks,
        private readonly CommentRepositoryInterface $comments,
        private readonly AttachmentRepositoryInterface $attachments,
    ) {}

    public function resolve(string $type, int $id, bool $includeTrashed = false,): array {
        $resource = match ($type) {
            'workspace' => $this->workspaces->findForAuthorization($id, $includeTrashed),

            'folder' => $this->folders->find($id),

            'list' => $this->lists->findForAuthorization($id, $includeTrashed),

            'task' => $this->tasks->findForAuthorization($id, $includeTrashed),

            'comment' => $this->comments->findForAuthorization($id),

            'attachment' => $this->attachments->findForAuthorization($id),

            default => throw new \InvalidArgumentException("Unsupported authorization resource: {$type}"),
        };

        if (!$resource) {
            throw (new ModelNotFoundException())->setModel($type, [$id]);
        }

        return match ($type) {
            'workspace' => [
                'workspace_id' => (int) $resource->id,
                'creator_id' => null,
            ],

            'folder', 'list' => [
                'workspace_id' => (int) $resource->workspace_id,
                'creator_id' => (int) $resource->created_by,
            ],

            'task' => [
                'workspace_id' => (int) $resource->list->workspace_id,
                'creator_id' => (int) $resource->created_by,
            ],

            'comment' => [
                'workspace_id' => (int) $resource->task->list->workspace_id,
                'creator_id' => (int) $resource->user_id,
            ],

            'attachment' => [
                'workspace_id' => (int) $resource->task->list->workspace_id,
                'creator_id' => (int) $resource->user_id,
            ],
        };
    }
}
