<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminLTEUserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any adminlteuser.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @return mixed
     */
    public function viewAny(AdminLTEUser $user)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectAdminLTE = new AdminLTE();
        $permissions = $objectAdminLTE->getUserPermissionData();

        if (isset($permissions['__adminlte_menu']))
        {
            if (isset($permissions['__adminlte_menu']['adminlteuser']))
            {
                $has_permission = $permissions['__adminlte_menu']['adminlteuser'];
            }
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can view the adminlteuser.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE\AdminLTEUser  $adminlteuser
     * @return mixed
     */
    public function view(AdminLTEUser $user, AdminLTEUser $adminlteuser)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectAdminLTE = new AdminLTE();
        $permissions = $objectAdminLTE->getUserPermissionData();

        if (isset($permissions['__adminlte_model']))
        {
            if (isset($permissions['__adminlte_model']['AdminLTEUser-read'])) {
                $has_permission = $permissions['__adminlte_model']['AdminLTEUser-read'];
            }
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can create adminlteuser.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @return mixed
     */
    public function create(AdminLTEUser $user)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectAdminLTE = new AdminLTE();
        $permissions = $objectAdminLTE->getUserPermissionData();

        if (isset($permissions['__adminlte_model']))
        {
            if (isset($permissions['__adminlte_model']['AdminLTEUser-create'])) {
                $has_permission = $permissions['__adminlte_model']['AdminLTEUser-create'];
            }
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can update the adminlteuser.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE\AdminLTEUser  $adminlteuser
     * @return mixed
     */
    public function update(AdminLTEUser $user, AdminLTEUser $adminlteuser)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectAdminLTE = new AdminLTE();
        $permissions = $objectAdminLTE->getUserPermissionData();

        if (isset($permissions['__adminlte_model']))
        {
            if (isset($permissions['__adminlte_model']['AdminLTEUser-update'])) {
                $has_permission = $permissions['__adminlte_model']['AdminLTEUser-update'];
            }
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can delete the adminlteuser.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE\AdminLTEUser  $adminlteuser
     * @return mixed
     */
    public function delete(AdminLTEUser $user, AdminLTEUser $adminlteuser)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }
        
        $has_permission = false;
        
        $objectAdminLTE = new AdminLTE();
        $permissions = $objectAdminLTE->getUserPermissionData();

        if (isset($permissions['__adminlte_model']))
        {
            if (isset($permissions['__adminlte_model']['AdminLTEUser-delete'])) {
                $has_permission = $permissions['__adminlte_model']['AdminLTEUser-delete'];
            }
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can restore the adminlteuser.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE\AdminLTEUser  $adminlteuser
     * @return mixed
     */
    public function restore(AdminLTEUser $user, AdminLTEUser $adminlteuser)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the adminlteuser.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE\AdminLTEUser  $adminlteuser
     * @return mixed
     */
    public function forceDelete(AdminLTEUser $user, AdminLTEUser $adminlteuser)
    {
        //
    }
}
