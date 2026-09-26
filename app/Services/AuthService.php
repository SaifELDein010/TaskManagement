<?php

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWT;
use Spatie\Permission\Models\Role;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private JWT $jwt
    ) {
    }

    private function userResponse(User $user): array {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'roles' => $user->getRoleNames()->values(),
        ];
    }

    public function register(array $data): array {
        $user = $this->userRepository->create([
            'username' => $data['username'],
            'email' => $data['email'],
            'hash_password' => Hash::make($data['password']),
        ]);

        $roleName = $data['role'] ?? 'Super Admin';

        $role = Role::where('name', $roleName)
            ->where('guard_name', 'api')
            ->first();

        if (!$role) {
            throw new \RuntimeException('Role not found.');
        }

        $user->assignRole($role);

        return $this->userResponse($user);
    }

    public function login(array $data): array {
        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user) {
            throw new \RuntimeException('Invalid credentials');
        }

        if (!Hash::check($data['password'], $user->hash_password)) {
            throw new \RuntimeException('Invalid credentials');
        }

        $token = $this->jwt->fromUser($user);

        return [
            'token' => $token,
            'user' => $this->userResponse($user),
        ];
    }

    public function logout(): void {
        $this->jwt->invalidate();
    }

    public function me(): array {
        $user = $this->jwt->user();

        return $this->userResponse($user);
    }

    public function updatePassword(array $data): array {
        $user = $this->jwt->user();

        if (!Hash::check($data['current_password'],$user->hash_password)) {
            throw new \RuntimeException('Invalid credentials');
        }

        $user = $this->userRepository->updatePassword(
            $user,
            Hash::make($data['new_password'])
        );

        return $this->userResponse($user);
    }
}