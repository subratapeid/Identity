<?php

namespace Pagelyne\Identity\Services\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutService
{
    public function logout(Request $request, string $context): array
    {
        $config = config("identity.contexts.{$context}");

        if (!is_array($config)) {
            return [
                'success' => false,
                'message' => "Identity context [{$context}] is not configured.",
            ];
        }

        $guard = $config['guard'] ?? null;

        if (!$guard) {
            return [
                'success' => false,
                'message' => "Identity guard is not configured for context [{$context}].",
            ];
        }

        try {
            Auth::guard($guard)->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return [
                'success' => true,
                'message' => 'Logout successful.',
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => 'Unable to logout.',
            ];
        }
    }
}