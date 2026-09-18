<?php

namespace App\Modules\Identity\Services;

use Illuminate\Http\Request;
use App\Modules\Identity\Repositories\Contracts\RoleRepositoryInterface;

class RoleService
{
    /**
     * Role Repository Instance
     *
     * @var RoleRepositoryInterface
     */
    protected RoleRepositoryInterface $roleRepository;

    /**
     * Constructor
     *
     * @param RoleRepositoryInterface $roleRepository
     */
    public function __construct(
        RoleRepositoryInterface $roleRepository
    ) {
        $this->roleRepository = $roleRepository;
    }

    /**
     * Display Role Listing
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return $this->roleRepository->index($request);
    }

    /**
     * Show Create Role Page
     *
     * @return mixed
     */
    public function create()
    {
        return $this->roleRepository->create();
    }

    /**
     * Store Role
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->roleRepository->store($request);
    }

    /**
     * Display Role Details
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        return $this->roleRepository->show($id);
    }

    /**
     * Show Edit Role Page
     *
     * @param int $id
     * @return mixed
     */
    public function edit(int $id)
    {
        return $this->roleRepository->edit($id);
    }

    /**
     * Update Role
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function update(Request $request, int $id)
    {
        return $this->roleRepository->update($request, $id);
    }

    /**
     * Delete Role
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        return $this->roleRepository->destroy($id);
    }

    /**
     * Assign Permissions To Role
     *
     * @param Request $request
     * @return mixed
     */
    public function assignPermissions(Request $request)
    {
        return $this->roleRepository->assignPermissions($request);
    }

    /**
     * Remove Permissions From Role
     *
     * @param Request $request
     * @return mixed
     */
    public function removePermissions(Request $request)
    {
        return $this->roleRepository->removePermissions($request);
    }

    /**
     * Get Role Permissions
     *
     * @param int $roleId
     * @return mixed
     */
    public function permissions(int $roleId)
    {
        return $this->roleRepository->permissions($roleId);
    }
}