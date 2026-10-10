<?php

namespace App\Jobs;

use App\Repositories\Contracts\ActivityLogRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExportActivityLogJob implements ShouldQueue {
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(
        private readonly int $workspaceId,
        private readonly string $format,
    ) {}

    public function handle(ActivityLogRepositoryInterface $activityLogRepository,): void {
        $logs = $activityLogRepository->getWorkspaceLogsForExport($this->workspaceId);

        $filename = sprintf('activity-exports/workspace-%d/activity-%s.%s', $this->workspaceId, now()->format('Y-m-d-H-i-s'), $this->format,);

        if ($this->format === 'csv') {
            $this->exportCsv($filename, $logs);

            return;
        }

        $this->exportJson($filename, $logs);
    }

    private function exportJson(string $filename, $logs): void {
        $data = $logs->map(function ($log) {
            return [
                'id' => $log->id,
                'workspace_id' => $log->workspace_id,
                'actor' => $log->actor ? [
                    'id' => $log->actor->id,
                    'username' => $log->actor->username,
                ] : null,
                'action' => $log->action,
                'subject' => [
                    'type' => $log->subject_type,
                    'id' => $log->subject_id,
                    'data' => $log->subject,
                ],
                'metadata' => $log->metadata,
                'created_at' => $log->created_at?->toISOString(),
            ];
        });

        Storage::put($filename, $data->toJson(JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    private function exportCsv(string $filename, $logs): void {
        $handle = fopen('php://temp', 'w+');

        fputcsv($handle, [
            'id',
            'workspace_id',
            'actor_id',
            'actor_username',
            'action',
            'subject_type',
            'subject_id',
            'metadata',
            'created_at',
        ]);

        foreach ($logs as $log) {
            fputcsv($handle, [
                $log->id,
                $log->workspace_id,
                $log->actor_id,
                $log->actor?->username,
                $log->action,
                $log->subject_type,
                $log->subject_id,
                json_encode(
                    $log->metadata,
                    JSON_UNESCAPED_UNICODE
                ),
                $log->created_at?->toISOString(),
            ]);
        }

        rewind($handle);

        Storage::put($filename, stream_get_contents($handle));

        fclose($handle);
    }
}