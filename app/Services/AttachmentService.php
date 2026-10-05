<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\User;
use App\Repositories\Contracts\AttachmentRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttachmentService {
    private string $disk = 'local';

    public function __construct(private AttachmentRepositoryInterface $attachmentRepository) {}

    public function create(User $user, int $taskId, UploadedFile $file): Attachment {
        $hash = hash_file('sha256', $file->getRealPath());

        $filename = $file->hashName();

        $path = $file->storeAs("tasks/{$taskId}/attachments", $filename, $this->disk);

        return $this->attachmentRepository->create([
            'task_id' => $taskId,
            'user_id' => $user->id,
            'original_filename' => $file->getClientOriginalName(),
            'filename' => $filename,
            'path' => $path,
            'disk' => $this->disk,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'hash' => $hash,
        ]);
    }

    public function getByTask(int $taskId): Collection {
        return $this->attachmentRepository->getByTaskId($taskId);
    }

   public function download(Attachment $attachment): StreamedResponse {
        $disk = Storage::disk($attachment->disk);

        if (!$disk->exists($attachment->path)) {
            abort(404, 'Attachment file not found.');
        }

        return response()->streamDownload(
            function () use ($disk, $attachment) {$stream = $disk->readStream($attachment->path);

                if ($stream === false) {
                    abort(404, 'Attachment file not found.');
                }

                fpassthru($stream);

                fclose($stream);

            }, $attachment->original_filename,['Content-Type' => $attachment->mime_type,]
        );
    }

    public function delete(Attachment $attachment): bool {
        $disk = Storage::disk($attachment->disk);

        if ($disk->exists($attachment->path)) {
            $disk->delete($attachment->path);
        }

        return $this->attachmentRepository->delete($attachment);
    }
}