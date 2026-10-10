<?php

namespace App\Activities\Membership;

use App\Activitiess\Activity;
use App\Models\User;

final class MemberRemoved implements Activity {
    public function __construct(private readonly int $userId,) {}

    public function action(): string {
        return 'member.removed';
    }

    public function metadata(): array {
        return [
            'user_id' => $this->userId,
        ];
    }

    public function subjectType(): string {
        return User::class;
    }
}