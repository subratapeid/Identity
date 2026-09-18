<?php

namespace App\Modules\Identity\Repositories;

use Illuminate\Http\Request;
use App\Modules\Identity\Models\User;

class ProfileRepository
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
     * Display Profile
     */
    public function show()
    {
        //
    }

    /**
     * Show Edit Profile Form
     */
    public function edit()
    {
        //
    }

    /**
     * Update Profile
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Update Profile Image
     */
    public function updateImage(Request $request)
    {
        //
    }

    /**
     * Remove Profile Image
     */
    public function removeImage()
    {
        //
    }

    /**
     * User Activities
     */
    public function activities()
    {
        //
    }
}