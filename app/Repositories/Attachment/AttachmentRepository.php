<?php

namespace App\Repositories;

use App\Models\Attachment;
use App\Repositories\Contracts\AttachmentRepositoryInterface;
use Illuminate\Support\Collection;

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
}