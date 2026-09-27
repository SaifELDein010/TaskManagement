<?php

namespace App\Repositories\User;

use App\Models\User;

class UserRepository implements UserRepositoryInterface {
    public function create(array $data): User {
        return User::create($data);
    }

    public function findByEmail(string $email): ?User {
        return User::where('email', $email)->first();
    }

    public function updatePassword(User $user, string $hashedPassword): User {
        $user->update([
            'hash_password' => $hashedPassword,
        ]);

        return $user;
    }
}