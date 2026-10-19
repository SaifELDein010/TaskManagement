<?php

namespace App\Http\Controllers;

use App\Http\Requests\Notification\UpdateNotificationPreferencesRequest;
use App\Http\Requests\Notification\GetNotificationRequest;
use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller {
    public function __construct(private NotificationService $notificationService) {}

    public function index(GetNotificationRequest $request): JsonResponse {
        $read = array_key_exists('read', $request->validated()) ? filter_var($request->validated('read'), FILTER_VALIDATE_BOOLEAN) : null;

        return response()->json([
            'data' => $this->notificationService->getForUser($request->user(), $read, $request->validated('type') ?? null),
        ]);
    }

    public function preferences(Request $request): JsonResponse {
        return response()->json([
            'data' => $this->notificationService->getPreferences($request->user()),
        ]);
    }

    public function updatePreferences(UpdateNotificationPreferencesRequest $request): JsonResponse {
        return response()->json([
            'message' => 'Notification preferences updated successfully.',
            'data' => $this->notificationService->updatePreferences($request->user(), $request->validated('preferences')),
        ]);
    }
}