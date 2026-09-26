<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {
    }

    public function register(RegisterRequest $request) {
        $user = $this->authService->register($request->validated());

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user,
        ], 201);
    }

    public function login(LoginRequest $request) {
        $result = $this->authService->login($request->validated());

        return response()->json($result, 200);
    }

    public function logout() {
        $this->authService->logout();

        return response()->json([
            'message' => 'Successfully logged out',
        ], 200);
    }

    public function me() {
        return response()->json([
            'message' => 'Successfully get authenticate profile',
            'user' => $this->authService->me(),
        ], 200);
    }

    public function updatePassword(ChangePasswordRequest $request) {
        $user = $this->authService->updatePassword($request->validated());

        return response()->json([
            'message' => 'Password updated successfully',
            'user' => $user,
        ], 200);
    }
}