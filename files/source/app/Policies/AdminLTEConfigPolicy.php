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
        $menu_permissions = $objectAdminLTE->getUserMenuPermissions();

        if (isset($menu_permissions['adminlteconfig']))
        {
            $has_permission = $menu_permissions['adminlteconfig'];
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
        $model_permissions = $objectAdminLTE->getUserModelPermissions();

        if (isset($model_permissions['adminlteconfig']))
        {
            $tokens = explode(',', $model_permissions['adminlteconfig']);
            $has_permission = in_array('read', $tokens);
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
        $model_permissions = $objectAdminLTE->getUserModelPermissions();

        if (isset($model_permissions['adminlteconfig']))
        {
            $tokens = explode(',', $model_permissions['adminlteconfig']);
            $has_permission = in_array('create', $tokens);
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
        $model_permissions = $objectAdminLTE->getUserModelPermissions();

        if (isset($model_permissions['adminlteconfig']))
        {
            $tokens = explode(',', $model_permissions['adminlteconfig']);
            $has_permission = in_array('update', $tokens);
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
        $model_permissions = $objectAdminLTE->getUserModelPermissions();

        if (isset($model_permissions['adminlteconfig']))
        {
            $tokens = explode(',', $model_permissions['adminlteconfig']);
            $has_permission = in_array('delete', $tokens);
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