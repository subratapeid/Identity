<?php

namespace App\Modules\Identity\Services;

use App\Modules\Identity\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserService
{
    /**
     * User Repository Instance
     */
    protected UserRepository $userRepository;

    /**
     * Constructor
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display User Listing
     */
    public function index(Request $request)
    {
        return $this->userRepository->index($request);
    }

    /**
     * Show Create User Form
     */
    public function create()
    {
        return $this->userRepository->create();
    }

    /**
     * Store User
     */
    public function store(Request $request)
    {
        return $this->userRepository->store($request);
    }

    /**
     * Display User Details
     */
    public function show(int $id)
    {
        return $this->userRepository->show($id);
    }

    /**
     * Show Edit User Form
     */
    public function edit(int $id)
    {
        return $this->userRepository->edit($id);
    }

    /**
     * Update User
     */
    public function update(Request $request, int $id)
    {
        return $this->userRepository->update($request, $id);
    }

    /**
     * Delete User
     */
    public function destroy(int $id)
    {
        return $this->userRepository->destroy($id);
    }
}