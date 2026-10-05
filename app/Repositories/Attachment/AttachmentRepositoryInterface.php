<?php

namespace App\Repositories\Contracts;

use App\Models\Attachment;
use Illuminate\Support\Collection;

interface AttachmentRepositoryInterface {
    public function create(array $data): Attachment;
    public function findById(int $id): ?Attachment;
    public function getByTaskId(int $taskId): Collection;
    public function delete(Attachment $attachment): bool;
}