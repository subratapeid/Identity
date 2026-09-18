<?php

namespace App\Modules\Identity\Services;

use Illuminate\Http\Request;
use App\Modules\Identity\Repositories\Contracts\AuthRepositoryInterface;

class RegisterService
{
    /**
     * Auth Repository Instance
     *
     * @var AuthRepositoryInterface
     */
    protected AuthRepositoryInterface $authRepository;


    /**
     * Constructor
     *
     * @param AuthRepositoryInterface $authRepository
     */
    public function __construct(
        AuthRepositoryInterface $authRepository
    ) {
        $this->authRepository = $authRepository;
    }


    /**
     * Show Registration Page
     *
     * @return mixed
     */
    public function create()
    {
        return $this->authRepository->createRegister();
    }


    /**
     * Register New User
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->authRepository->register($request);
    }


    /**
     * Verify User Email
     *
     * @param string $token
     * @return mixed
     */
    public function verify(string $token)
    {
        return $this->authRepository->verify($token);
    }


    /**
     * Resend Email Verification
     *
     * @param Request $request
     * @return mixed
     */
    public function resend(Request $request)
    {
        return $this->authRepository->resendVerification($request);
    }
}