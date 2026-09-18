<?php

namespace App\Modules\Identity\Repositories;

use Illuminate\Http\Request;
use App\Modules\Identity\Models\User;

class UserRepository
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
     * Display User Listing
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        // Fetch users
    }

    /**
     * Show Create User Form
     *
     * @return mixed
     */
    public function create()
    {
        // Load create page data
    }

    /**
     * Store User
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        // Store user
    }

    /**
     * Display User Details
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        // Get user details
    }

    /**
     * Show Edit User Form
     *
     * @param int $id
     * @return mixed
     */
    public function edit(int $id)
    {
        // Get edit data
    }

    /**
     * Update User
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function update(Request $request, int $id)
    {
        // Update user
    }

    /**
     * Delete User
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        // Delete user
    }
}