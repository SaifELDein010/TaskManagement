<?php

namespace App\Http\Middleware;

use App\Services\Policy\PolicyService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthorizationMiddleware {
    public function __construct(
        private readonly PolicyService $policyService
    ) {
    }

    public function handle(Request $request, Closure $next, string $permission): Response {
        $user = $request->user();

        if ($user === null) {
            return response()->json([
                'message' => 'Unauthenticated',
                'data' => null,
            ], 401);
        }

        $authorized = $this->policyService->authorize(
            $user,
            $permission
        );

        if (!$authorized) {
            return response()->json([
                'message' => 'You are not authorized to perform this action.',
                'data' => null,
            ], 403);
        }

        return $next($request);
    }
}