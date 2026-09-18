<?php

namespace App\Modules\Identity\Repositories;

use Illuminate\Http\Request;
use App\Modules\Identity\Models\Permission;

class PermissionRepository
{
    /**
     * Permission Model Instance
     */
    protected Permission $permission;

    /**
     * Constructor
     */
    public function __construct(Permission $permission)
    {
        $this->permission = $permission;
    }

    /**
     * Display Permission Listing
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show Create Permission Form
     */
    public function create()
    {
        //
    }

    /**
     * Store Permission
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display Permission Details
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show Edit Permission Form
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update Permission
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Delete Permission
     */
    public function destroy(int $id)
    {
        //
    }

    /**
     * Assign Permission To Role
     */
    public function assignToRole(Request $request)
    {
        //
    }

    /**
     * Remove Permission From Role
     */
    public function removeFromRole(Request $request)
    {
        //
    }

    /**
     * Get Role Permissions
     */
    public function rolePermissions(int $roleId)
    {
        //
    }
}