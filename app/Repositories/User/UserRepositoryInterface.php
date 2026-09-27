<?php

namespace App\Repositories\User;

use App\Models\User;

interface UserRepositoryInterface {
    public function create(array $data): User;

    public function findByEmail(string $email): ?User;

    public function updatePassword(User $user, string $hashedPassword): User;
}