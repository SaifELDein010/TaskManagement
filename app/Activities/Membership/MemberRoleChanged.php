<?php

namespace App\Activities\Membership;

use App\Activitiess\Activity;
use App\Models\User;

final class MemberRoleChanged implements Activity {
    public function __construct(
        private readonly string $oldRole,
        private readonly string $newRole,
    ) {}

    public function action(): string {
        return 'member.role_changed';
    }

    public function metadata(): array {
        return [
            'old_role' => $this->oldRole,
            'new_role' => $this->newRole,
        ];
    }

    public function subjectType(): string {
        return User::class;
    }
}