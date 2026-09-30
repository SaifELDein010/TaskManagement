<?php

namespace App\Repositories\List;

use App\Models\Folder;
use App\Models\TaskList;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ListRepository implements ListRepositoryInterface{
    public function create(array $data){
        return TaskList::create($data);
    }

    public function find(int $id): ?TaskList{
        return TaskList::query()
            ->where('id', $id)
            ->first();
    }

    public function findForUser(User $user, int $id): ?TaskList {
        return TaskList::query()
            ->where('lists.id', $id)
            ->whereHas('workspace.members', function ($query) use ($user) {$query->where('user_id', $user->id);})
            ->first();
    }

    public function getAllForUser(User $user){
        return TaskList::query()
            ->whereHas('workspace.members', function ($query) use ($user) {$query->where('user_id', $user->id);})
            ->orderBy('workspace_id')
            ->orderBy('folder_id')
            ->orderBy('id')
            ->get();
    }

    public function update(TaskList $list, array $data) {
        $list->update($data);

        return $list->refresh();
    }

    public function delete(TaskList $list){
        return $list->delete();
    }

    public function findFolder(int $folderId){
        return Folder::query()
            ->where('id', $folderId)
            ->first();
    }

    public function findForRestore(int $id): ?TaskList {
        return TaskList::withTrashed()
            ->where('id', $id)
            ->first();
    }

    public function restore(TaskList $list) {
        $list->restore();

        return $list->refresh();
    }

    public function updateWorkflow(TaskList $list, array $workflow, ?array $defaults): TaskList {
        return DB::transaction(function () use ($list,$workflow,$defaults) {$data = ['workflow' => $workflow,];

            if ($defaults !== null) {
                $data['task_defaults'] = $defaults;
            }

            $list->update($data);

            return $list->refresh();
         });
    }
}