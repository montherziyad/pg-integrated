<?php

namespace App\Http\Middleware;

use App\Support\RoleScreenPermissions;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserCanAccessScreen
{
    public function handle(Request $request, Closure $next, ?string $screen = null): Response
    {
        $user = $request->user();
        $screen = $screen ?: RoleScreenPermissions::screenForRouteName($request->route()?->getName());

        if (! $screen) {
            return $next($request);
        }

        if (! $user || ! $user->canAccessScreen($screen)) {
            abort(403, 'You do not have permission to access this screen.');
        }

        return $next($request);
    }
}
