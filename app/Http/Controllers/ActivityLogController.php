<?php

namespace App\Http\Controllers;

use App\Models\Workspace;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use App\Http\Requests\ExportActivityLogRequest;

class ActivityLogController extends Controller {
    public function __construct(private readonly ActivityLogService $activityLogService,) {}

    public function index(Request $request, Workspace $workspace) {
        $perPage = $request->integer('per_page', 20);

        $perPage = min(max($perPage, 1), 100);

        $logs = $this->activityLogService->getWorkspaceLogs(workspaceId: $workspace->id, perPage: $perPage,);

        return response()->json($logs);
    }

    public function export(ExportActivityLogRequest $request, int $workspaceId,) {
        $this->activityLogService->export(workspaceId: $workspaceId, format: $request->string('format')->toString(),);

        return response()->json([
            'message' => 'Activity log export has been queued.',
        ], 202);
    }
}