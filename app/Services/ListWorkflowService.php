<?php

namespace App\Services;

use App\Models\TaskList;
use App\Models\User;
use App\Repositories\List\ListRepositoryInterface;
use InvalidArgumentException;

class ListWorkflowService
{
    public function __construct(private ListRepositoryInterface $listRepository) {}

    public function update(User $user, int $id, array $data): ?TaskList {
        $list = $this->listRepository->findForUser($user,$id);

        if (!$list) {
            return null;
        }

        $statusIds = array_column($data['statuses'],'id');
        $statusIds = array_values(array_unique($statusIds));

        foreach ($data['transitions'] as $transition) {
            if (!in_array($transition['from'],$statusIds,true)) {
                throw new InvalidArgumentException(
                    "Invalid transition source status: {$transition['from']}."
                );
            }

            if (!in_array($transition['to'],$statusIds,true)) {
                throw new InvalidArgumentException(
                    "Invalid transition target status: {$transition['to']}."
                );
            }

            if ($transition['from'] === $transition['to']) {
                throw new InvalidArgumentException(
                    "A status cannot transition to itself."
                );
            }
        }

        $workflow = [
            'statuses' => $data['statuses'],
            'transitions' => $data['transitions'],
        ];

        $defaults = $data['defaults'] ?? null;

        return $this->listRepository->updateWorkflow($list, $workflow, $defaults);
    }
}