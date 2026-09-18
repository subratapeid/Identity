<?php

namespace App\Modules\Identity\Services;

use Illuminate\Http\Request;
use App\Modules\Identity\Repositories\Contracts\ProfileRepositoryInterface;

class ProfileService
{
    /**
     * Profile Repository Instance
     *
     * @var ProfileRepositoryInterface
     */
    protected ProfileRepositoryInterface $profileRepository;

    /**
     * Constructor
     *
     * @param ProfileRepositoryInterface $profileRepository
     */
    public function __construct(
        ProfileRepositoryInterface $profileRepository
    ) {
        $this->profileRepository = $profileRepository;
    }

    /**
     * Display User Profile
     *
     * @return mixed
     */
    public function show()
    {
        return $this->profileRepository->show();
    }

    /**
     * Show Edit Profile Page
     *
     * @return mixed
     */
    public function edit()
    {
        return $this->profileRepository->edit();
    }

    /**
     * Update User Profile
     *
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request)
    {
        return $this->profileRepository->update($request);
    }

    /**
     * Update Profile Image
     *
     * @param Request $request
     * @return mixed
     */
    public function updateImage(Request $request)
    {
        return $this->profileRepository->updateImage($request);
    }

    /**
     * Remove Profile Image
     *
     * @return mixed
     */
    public function removeImage()
    {
        return $this->profileRepository->removeImage();
    }

    /**
     * Get User Activity History
     *
     * @return mixed
     */
    public function activities()
    {
        return $this->profileRepository->activities();
    }
}