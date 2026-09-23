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
            is_string($guard) && $guard !== '',
            500,
            "Identity guard is not configured for context [{$context}]."
        );

        abort_unless(
            is_string($loginRoute) && $loginRoute !== '',
            500,
            "Identity login route is not configured for context [{$context}]."
        );

        abort_unless(
            array_key_exists($guard, config('auth.guards', [])),
            500,
            "Identity guard [{$guard}] is not configured in auth.guards."
        );

        $request->attributes->set('identity.context', $context);
        $request->attributes->set('identity.guard', $guard);

        $auth = auth()->guard($guard);

        if (!$auth->check()) {
            return redirect()->route($loginRoute);
        }

        $request->attributes->set(
            'identity.user',
            $auth->user()
        );

        return $next($request);
    }
}