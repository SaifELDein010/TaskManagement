<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attachments\StoreAttachmentRequest;
use App\Models\Attachment;
use App\Services\AttachmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AttachmentsController extends Controller
{
    public function __construct(private AttachmentService $attachmentService) {}

    public function store(StoreAttachmentRequest $request, int $taskId): JsonResponse {
        $attachment = $this->attachmentService->create(
            user: $request->user(),
            taskId: $taskId,
            file: $request->file('file')
        );

        return response()->json([
            'message' => 'Attachment uploaded successfully.',
            'data' => $attachment,
        ], 201);
    }

    public function index(Request $request, int $taskId): JsonResponse {
        $attachments = $this->attachmentService->getByTask($taskId);

        return response()->json([
            'data' => $attachments,
        ]);
    }

    public function download(Request $request, Attachment $attachment): StreamedResponse {
        return $this->attachmentService->download(attachment: $attachment);
    }

    public function destroy(Request $request, Attachment $attachment): JsonResponse {
        $this->attachmentService->delete(attachment: $attachment);

        return response()->json([
            'message' => 'Attachment deleted successfully.',
        ]);
    }
}