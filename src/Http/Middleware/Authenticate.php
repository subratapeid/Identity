<?php

declare(strict_types=1);

namespace Pagelyne\Identity\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Authenticate
{
    public function handle(
        Request $request,
        Closure $next,
        string $context
    ): Response {
        $config = config("identity.contexts.{$context}");

        abort_unless(
            is_array($config),
            500,
            "Identity context [{$context}] is not configured."
        );

        $guard = $config['guard'] ?? null;
        $loginRoute = $config['login_route'] ?? null;

        abort_unless(
            $guard,
            500,
            "Identity guard is not configured for context [{$context}]."
        );

        abort_unless(
            $loginRoute,
            500,
            "Identity login route is not configured for context [{$context}]."
        );

        if (!auth()->guard($guard)->check()) {
            return redirect()->route($loginRoute);
        }

        return $next($request);
    }
}