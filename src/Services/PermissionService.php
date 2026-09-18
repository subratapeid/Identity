<?php

namespace App\Modules\Identity\Services;

use Illuminate\Http\Request;
use App\Modules\Identity\Repositories\Contracts\PermissionRepositoryInterface;

class PermissionService
{
    /**
     * Permission Repository Instance
     *
     * @var PermissionRepositoryInterface
     */
    protected PermissionRepositoryInterface $permissionRepository;

    /**
     * Constructor
     *
     * @param PermissionRepositoryInterface $permissionRepository
     */
    public function __construct(
        PermissionRepositoryInterface $permissionRepository
    ) {
        $this->permissionRepository = $permissionRepository;
    }


    /**
     * Display Permission Listing
     *
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        return $this->permissionRepository->index($request);
    }


    /**
     * Show Create Permission Page
     *
     * @return mixed
     */
    public function create()
    {
        return $this->permissionRepository->create();
    }


    /**
     * Store Permission
     *
     * @param Request $request
     * @return mixed
     */
    public function store(Request $request)
    {
        return $this->permissionRepository->store($request);
    }


    /**
     * Display Permission Details
     *
     * @param int $id
     * @return mixed
     */
    public function show(int $id)
    {
        return $this->permissionRepository->show($id);
    }


    /**
     * Show Edit Permission Page
     *
     * @param int $id
     * @return mixed
     */
    public function edit(int $id)
    {
        return $this->permissionRepository->edit($id);
    }


    /**
     * Update Permission
     *
     * @param Request $request
     * @param int $id
     * @return mixed
     */
    public function update(Request $request, int $id)
    {
        return $this->permissionRepository->update($request, $id);
    }


    /**
     * Delete Permission
     *
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id)
    {
        return $this->permissionRepository->destroy($id);
    }


    /**
     * Assign Permission To Role
     *
     * @param Request $request
     * @return mixed
     */
    public function assignToRole(Request $request)
    {
        return $this->permissionRepository->assignToRole($request);
    }


    /**
     * Remove Permission From Role
     *
     * @param Request $request
     * @return mixed
     */
    public function removeFromRole(Request $request)
    {
        return $this->permissionRepository->removeFromRole($request);
    }


    /**
     * Get Role Permission List
     *
     * @param int $roleId
     * @return mixed
     */
    public function rolePermissions(int $roleId)
    {
        return $this->permissionRepository->rolePermissions($roleId);
    }
}