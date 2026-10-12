<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\WorkspaceMember\WorkspaceMemberRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;

class PolicyService {
    private const SUPER_ADMIN = 'super-admin';
    private const ADMIN = 'admin';

    public function __construct(
        private readonly WorkspaceMemberRepositoryInterface $members,
        private readonly AuthorizationResourceResolver $resolver,
    ) {}

    public function isSuperAdmin(User $user): bool {
        return $user->hasRole(self::SUPER_ADMIN, 'api');
    }

    public function authorize(User $user, string $permission, ?int $workspaceId = null,): void {
        if ($this->isSuperAdmin($user)) {
            return;
        }

        if (!$user->hasPermissionTo($permission, 'api')) {
            throw new AuthorizationException(
                'You do not have permission to perform this action.'
            );
        }

        if ($workspaceId !== null) {
            $this->assertWorkspaceMember($user, $workspaceId);
        }
    }

    public function authorizeResource(User $user, string $permission, string $resourceType, int $resourceId, bool $includeTrashed = false,): void {
        $resource = $this->resolver->resolve($resourceType, $resourceId, $includeTrashed);

        $workspaceId = $resource['workspace_id'];
        $creatorId = $resource['creator_id'];

        if (in_array($permission, [
            'comment.create',
            'comment.view',
            'attachment.upload',
            'attachment.view',
        ], true)) {
            $this->assertWorkspaceMember($user, $workspaceId);

            return;
        }

        $this->authorize($user, $permission, $workspaceId);

        if ($permission === 'comment.update') {
            $this->assertCreator($user, $creatorId);

            return;
        }

        if (in_array($permission, [
            'comment.delete',
            'attachment.delete',
        ], true)) {
            $this->assertCreatorOrSuperAdmin($user, $creatorId);

            return;
        }

        if (in_array($permission, [
            'task.delete',
            'task.restore',
            'list.delete',
            'list.restore',
        ], true)) {
            $this->assertCreatorOrSuperAdmin($user, $creatorId);
        }
    }

    public function authorizeRoleManagement(User $user): void {
        if (!$this->isSuperAdmin($user)) {
            throw new AuthorizationException(
                'Only a Super Admin can manage global roles.'
            );
        }
    }

    public function authorizeMemberRemoval(User $actor, int $workspaceId, int $targetUserId,): void {
        $this->authorize($actor, 'workspace.members.remove', $workspaceId);

        $targetMembership = $this->members->find($workspaceId, $targetUserId);

        if (!$targetMembership) {
            throw new AuthorizationException(
                'The user is not a member of this workspace.'
            );
        }

        if ($targetUserId === (int) $actor->id && $targetMembership->is_owner) {
            throw new AuthorizationException(
                'A workspace owner cannot remove themselves.'
            );
        }

        if ($targetMembership->is_owner && !$this->isSuperAdmin($actor)) {
            throw new AuthorizationException(
                'Only a Super Admin can remove a workspace owner.'
            );
        }
    }

    public function authorizeOwnerAssignment(User $actor, int $workspaceId, bool $assignOwner,): void {
        $this->authorize($actor, 'workspace.members.update', $workspaceId);

        if ($assignOwner && !$this->isSuperAdmin($actor) && !$actor->hasRole(self::ADMIN, 'api')) {
            throw new AuthorizationException(
                'Only an Admin or Super Admin can assign workspace ownership.'
            );
        }
    }

    private function assertWorkspaceMember(User $user, int $workspaceId,): void {
        if ($this->isSuperAdmin($user)) {
            return;
        }

        if (!$this->members->find($workspaceId, (int) $user->id)) {
            throw new AuthorizationException(
                'You are not a member of this workspace.'
            );
        }
    }

    private function assertCreator(User $user, ?int $creatorId,): void {
        if ($creatorId === null || (int) $user->id !== $creatorId) {
            throw new AuthorizationException(
                'Only the creator can perform this action.'
            );
        }
    }

    private function assertCreatorOrSuperAdmin(User $user, ?int $creatorId,): void {
        if (!$this->isSuperAdmin($user) && ($creatorId === null || (int) $user->id !== $creatorId)) {
            throw new AuthorizationException(
                'Only the creator or a Super Admin can perform this action.'
            );
        }
    }
}
