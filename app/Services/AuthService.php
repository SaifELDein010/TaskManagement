<?php

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\Role\RoleRepositoryInterface;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWT;

class AuthService
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private RoleRepositoryInterface $roleRepository,
        private JWT $jwt
    ) {
    }

    private function userResponse(User $user): array {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role_id' => $user->role_id,
        ];
    }

    public function register(array $data) {
        $roleName = $data['role'] ?? 'Super Admin';

        $role = $this->roleRepository->findByName($roleName);

        if (!$role) {
            throw new \RuntimeException('Role not found.');
        }

        $user = $this->userRepository->create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role_id' => $role->id,
        ]);

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
}