<?php

namespace App\Modules\Identity\Repositories;

use Illuminate\Http\Request;
use App\Modules\Identity\Models\User;

class AuthRepository
{
    /**
     * User Model Instance
     */
    protected User $user;

    /**
     * Constructor
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Show Login Page
     */
    public function create()
    {
        //
    }

    /**
     * Login User
     */
    public function login(Request $request)
    {
        //
    }

    /**
     * Login Status
     */
    public function status()
    {
        //
    }

    /**
     * Logout User
     */
    public function logout(Request $request)
    {
        //
    }

    /**
     * Show Registration Page
     */
    public function createRegister()
    {
        //
    }

    /**
     * Register User
     */
    public function register(Request $request)
    {
        //
    }

    /**
     * Verify Email
     */
    public function verify(string $token)
    {
        //
    }

    /**
     * Resend Verification Email
     */
    public function resendVerification(Request $request)
    {
        //
    }

    /**
     * Show Forgot Password Page
     */
    public function createForgotPassword()
    {
        //
    }

    /**
     * Send Password Reset Link
     */
    public function forgotPassword(Request $request)
    {
        //
    }

    /**
     * Show Reset Password Page
     */
    public function createResetPassword(string $token)
    {
        //
    }

    /**
     * Reset Password
     */
    public function resetPassword(Request $request)
    {
        //
    }

    /**
     * Show Change Password Page
     */
    public function editPassword()
    {
        //
    }

    /**
     * Change Password
     */
    public function changePassword(Request $request)
    {
        //
    }
}