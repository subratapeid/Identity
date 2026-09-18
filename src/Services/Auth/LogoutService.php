<?php

namespace Identity\Services\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutService
{
    public function logout(Request $request): void
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
    }
}