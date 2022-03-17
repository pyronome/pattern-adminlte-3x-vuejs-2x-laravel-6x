<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\AdminLTE\AdminLTEMedia;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminLTEMediaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any adminltemedia.
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

        if (isset($menu_permissions['adminltemedia']))
        {
            $has_permission = $menu_permissions['adminltemedia'];
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can view the adminltemedia.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTEMedia  $adminltemedia
     * @return mixed
     */
    public function view(AdminLTEUser $user, AdminLTEMedia $adminltemedia)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectAdminLTE = new AdminLTE();
        $model_permissions = $objectAdminLTE->getUserModelPermissions();

        if (isset($model_permissions['adminltemedia']))
        {
            $tokens = explode(',', $model_permissions['adminltemedia']);
            $has_permission = in_array('read', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can create adminltemedia.
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

        if (isset($model_permissions['adminltemedia']))
        {
            $tokens = explode(',', $model_permissions['adminltemedia']);
            $has_permission = in_array('create', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can update the adminltemedia.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTEMedia  $adminltemedia
     * @return mixed
     */
    public function update(AdminLTEUser $user, AdminLTEMedia $adminltemedia)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectAdminLTE = new AdminLTE();
        $model_permissions = $objectAdminLTE->getUserModelPermissions();

        if (isset($model_permissions['adminltemedia']))
        {
            $tokens = explode(',', $model_permissions['adminltemedia']);
            $has_permission = in_array('update', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can delete the adminltemedia.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTEMedia  $adminltemedia
     * @return mixed
     */
    public function delete(AdminLTEUser $user, AdminLTEMedia $adminltemedia)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }
        
        $has_permission = false;
        
        $objectAdminLTE = new AdminLTE();
        $model_permissions = $objectAdminLTE->getUserModelPermissions();

        if (isset($model_permissions['adminltemedia']))
        {
            $tokens = explode(',', $model_permissions['adminltemedia']);
            $has_permission = in_array('delete', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can restore the adminltemedia.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTEMedia  $adminltemedia
     * @return mixed
     */
    public function restore(AdminLTEUser $user, AdminLTEMedia $adminltemedia)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the adminltemedia.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTEMedia  $adminltemedia
     * @return mixed
     */
    public function forceDelete(AdminLTEUser $user, AdminLTEMedia $adminltemedia)
    {
        //
    }
}