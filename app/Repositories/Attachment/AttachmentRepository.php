<?php

namespace App\Repositories;

use App\Models\Attachment;
use Illuminate\Support\Collection;
use App\Repositories\Attachment\AttachmentRepositoryInterface;

class AttachmentRepository implements AttachmentRepositoryInterface {
    public function create(array $data): Attachment {
        return Attachment::create($data);
    }

    public function findById(int $id): ?Attachment {
        return Attachment::find($id);
    }

    public function getByTaskId(int $taskId): Collection {
        return Attachment::query()
            ->where('task_id', $taskId)
            ->with('user')
            ->orderBy('created_at')
            ->get();
    }

    public function delete(Attachment $attachment): bool {
        return $attachment->delete();
    }

    public function findForAuthorization(int $id): ?Attachment {
        return Attachment::query()
            ->with(['task' => fn ($taskQuery) => $taskQuery->withTrashed()->with(['list' => fn ($listQuery) => $listQuery->withTrashed(),]),])
            ->find($id);
    }
}