<?php

namespace App\Http\Middleware;

use App\Services\PolicyService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthorizeAction{
    public function __construct(private readonly PolicyService $policyService,) {}

    public function handle(Request $request, Closure $next, string $permission,): Response {
        $user = Auth::guard('api')->user();

        abort_unless($user, 401, 'Unauthenticated.');

        $workspaceId = $request->route('workspaceId')
            ?? $request->route('workspace')
            ?? $request->route('id');

        $this->policyService->authorize($user, $permission, $workspaceId !== null ? (int) $workspaceId : null,);

        return $next($request);
    }
}
