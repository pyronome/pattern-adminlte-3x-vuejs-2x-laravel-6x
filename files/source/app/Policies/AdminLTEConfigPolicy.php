<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\AdminLTE\AdminLTEConfig;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminLTEConfigPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any adminlteconfig.
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
            if (isset($permissions['__adminlte_menu']['adminlteconfig']))
            {
                $has_permission = $permissions['__adminlte_menu']['adminlteconfig'];
            }
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can view the adminlteconfig.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE\AdminLTEConfig  $adminlteconfig
     * @return mixed
     */
    public function view(AdminLTEUser $user, AdminLTEConfig $adminlteconfig)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectAdminLTE = new AdminLTE();
        $permissions = $objectAdminLTE->getUserPermissionData();

        if (isset($permissions['__adminlte_model']))
        {
            if (isset($permissions['__adminlte_model']['AdminLTEConfig-read'])) {
                $has_permission = $permissions['__adminlte_model']['AdminLTEConfig-read'];
            }
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can create adminlteconfig.
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
            if (isset($permissions['__adminlte_model']['AdminLTEConfig-create'])) {
                $has_permission = $permissions['__adminlte_model']['AdminLTEConfig-create'];
            }
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can update the adminlteconfig.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE\AdminLTEConfig  $adminlteconfig
     * @return mixed
     */
    public function update(AdminLTEUser $user, AdminLTEConfig $adminlteconfig)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectAdminLTE = new AdminLTE();
        $permissions = $objectAdminLTE->getUserPermissionData();

        if (isset($permissions['__adminlte_model']))
        {
            if (isset($permissions['__adminlte_model']['AdminLTEConfig-update'])) {
                $has_permission = $permissions['__adminlte_model']['AdminLTEConfig-update'];
            }
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can delete the adminlteconfig.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE\AdminLTEConfig  $adminlteconfig
     * @return mixed
     */
    public function delete(AdminLTEUser $user, AdminLTEConfig $adminlteconfig)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }
        
        $has_permission = false;
        
        $objectAdminLTE = new AdminLTE();
        $permissions = $objectAdminLTE->getUserPermissionData();

        if (isset($permissions['__adminlte_model']))
        {
            if (isset($permissions['__adminlte_model']['AdminLTEConfig-delete'])) {
                $has_permission = $permissions['__adminlte_model']['AdminLTEConfig-delete'];
            }
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can restore the adminlteconfig.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE\AdminLTEConfig  $adminlteconfig
     * @return mixed
     */
    public function restore(AdminLTEUser $user, AdminLTEConfig $adminlteconfig)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the adminlteconfig.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE\AdminLTEConfig  $adminlteconfig
     * @return mixed
     */
    public function forceDelete(AdminLTEUser $user, AdminLTEConfig $adminlteconfig)
    {
        //
    }
}