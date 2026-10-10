<?php

namespace App\Http\Controllers;

use App\Http\Requests\Comments\StoreCommentRequest;
use App\Http\Requests\Comments\UpdateCommentRequest;
use App\Models\Comment;
use App\Services\CommentService;
use Illuminate\Http\JsonResponse;
use InvalidArgumentException;

class CommentsController extends Controller {
    public function __construct(private CommentService $commentService) {}

    public function store(StoreCommentRequest $request, int $taskId): JsonResponse {
        try {

            $comment = $this->commentService->create(
                taskId: $taskId,
                userId: $request->user()->id,
                data: $request->validated()
            );

            return response()->json([
                'message' => 'Comment created successfully.',
                'data' => $comment->load('user'),
            ], 201);

        } catch (InvalidArgumentException $e) {

            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        
        }
    }

    public function index(int $taskId): JsonResponse {
        $comments = $this->commentService->getByTask($taskId);

        return response()->json([
            'data' => $comments,
        ]);
    }

    public function update(UpdateCommentRequest $request, Comment $comment): JsonResponse {
        $comment = $this->commentService->update($comment, $request->validated());

        return response()->json([
            'message' => 'Comment updated successfully.',
            'data' => $comment->load('user'),
        ]);
    }

    public function destroy(Comment $comment): JsonResponse {
        $this->commentService->delete($comment);

        return response()->json([
            'message' => 'Comment deleted successfully.',
        ]);
    }
}