<?php

namespace App\Modules\Identity\Repositories;

use Illuminate\Http\Request;
use App\Modules\Identity\Models\User;

class RegisterRepository
{
    /**
     * User Model Instance
     *
     * @var User
     */
    protected User $user;

    /**
     * Constructor
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Show Registration Page
     *
     * @return mixed
     */
    public function create()
    {
        //
    }

    /**
     * Register New User
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Verify User Email
     *
     * @param string $token
     * @return mixed
     */
    public function verify(string $token)
    {
        //
    }

    /**
     * Resend Email Verification
     *
     * @param Request $request
     * @return mixed
     */
    public function resend(Request $request)
    {
        //
    }

    /**
     * Activate User Account
     *
     * @param int $userId
     * @return mixed
     */
    public function activate(int $userId)
    {
        //
    }

    /**
     * Deactivate User Account
     *
     * @param int $userId
     * @return mixed
     */
    public function deactivate(int $userId)
    {
        //
    }
}