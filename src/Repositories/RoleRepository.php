<?php

namespace App\Modules\Identity\Repositories;

use Illuminate\Http\Request;
use App\Modules\Identity\Models\Role;

class RoleRepository
{
    /**
     * Role Model Instance
     */
    protected Role $role;

    /**
     * Constructor
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * Display Role Listing
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show Create Role Form
     */
    public function create()
    {
        //
    }

    /**
     * Store Role
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display Role Details
     */
    public function show(int $id)
    {
        //
    }

    /**
     * Show Edit Role Form
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update Role
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Delete Role
     */
    public function destroy(int $id)
    {
        //
    }

    /**
     * Assign Permissions
     */
    public function assignPermissions(Request $request)
    {
        //
    }

    /**
     * Remove Permissions
     */
    public function removePermissions(Request $request)
    {
        //
    }

    /**
     * Get Role Permissions
     */
    public function permissions(int $roleId)
    {
        //
    }
}