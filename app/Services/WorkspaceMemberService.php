<?php

namespace App\Services;

use App\Models\WorkspaceMember;
use App\Repositories\WorkspaceMember\WorkspaceMemberRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use App\Activities\Membership\MemberAdded;
use App\Activities\Membership\MemberRoleChanged;
use App\Activities\Membership\MemberRemoved;

class WorkspaceMemberService {
    public function __construct(
        private WorkspaceMemberRepositoryInterface $workspaceMemberRepository,
        private ActivityLogService $activityLogService
    ) {}

    public function get(int $workspaceId, int $userId) {
        $workspaceMember = $this->workspaceMemberRepository->find($workspaceId, $userId);

        if (!$workspaceMember) {
            throw new ModelNotFoundException('Workspace member not found.');
        }

        return $workspaceMember;
    }

    public function create(int $workspaceId, int $userId, int $actorId) {
        return DB::transaction(function () use ($workspaceId, $userId, $actorId) {
            $existingMember = $this->workspaceMemberRepository->find($workspaceId, $userId);

            if ($existingMember) {
                throw new \DomainException(
                    'User is already a member of this workspace.'
                );
            }

            $member = $this->workspaceMemberRepository->create([
                'workspace_id' => $workspaceId,
                'user_id' => $userId,
                'is_owner' => false,
            ]);

            $this->activityLogService->create(
                workspaceId: $workspaceId,
                actorId: $actorId,
                activity: new MemberAdded(userId: $userId,),
                subjectId: $userId,
            );

            return $member;
        });
    }

    public function update(int $workspaceId, int $userId, array $data, int $actorId) {
        return DB::transaction(function () use ($workspaceId, $userId, $data, $actorId) {
            $workspaceMember = $this->get($workspaceId, $userId);

            $oldRoleId = $workspaceMember->role_id;
            $newRoleId = $data['role_id'] ?? $oldRoleId;

            $member = $this->workspaceMemberRepository->update($workspaceMember, $data);

            if ($oldRoleId !== $newRoleId) {
                $this->activityLogService->create(
                    workspaceId: $workspaceId,
                    actorId: $actorId,
                    activity: new MemberRoleChanged(oldRole: (string) $oldRoleId, newRole: (string) $newRoleId,),
                    subjectId: $userId,
                );
            }

            return $member;
        });
    }

    public function delete(int $workspaceId, int $userId, int $actorId) {
        return DB::transaction(function () use ($workspaceId, $userId, $actorId) {
            $workspaceMember = $this->get($workspaceId, $userId);

            $this->workspaceMemberRepository->delete($workspaceMember);

            $this->activityLogService->create(
                workspaceId: $workspaceId,
                actorId: $actorId,
                activity: new MemberRemoved(userId: $userId,),
                subjectId: $userId,
            );
        });
    }
}