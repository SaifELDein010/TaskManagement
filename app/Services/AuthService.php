<?php

namespace App\Services;

use App\Repositories\User\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWT;

class AuthService{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private JWT $jwt
    ) {}

    private function userResponse(User $user) {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
        ];
    }

    public function register(array $data) {

        $roleName = $data['role'] ?? 'Super Admin';
        
        $user = $this->userRepository->create([
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => hash::make($data['password']),
        ]);

        $user->assignRole($roleName);

        return $this->userResponse($user);
    }

    public function login(array $data): array {
        $user = $this->userRepository->findByEmail($data['email']);

        if (!$user) {
            throw new \RuntimeException('Invalid credentials');
        }

        if (!Hash::check($data['password'], $user->password)) {
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
        $user = auth('api')->user();

        return $this->userResponse($user);
    }

    public function updatePassword(array $data): array {
        $user = auth('api')->user();

        if (!Hash::check($data['current_password'],$user->password)) {
            throw new \RuntimeException('Invalid credentials');
        }

        $user = $this->userRepository->updatePassword($user, Hash::make($data['new_password']));

        return $this->userResponse($user);
    }
}