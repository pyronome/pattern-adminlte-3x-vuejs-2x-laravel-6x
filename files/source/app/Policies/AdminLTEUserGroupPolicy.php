<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\AdminLTE\AdminLTEUserGroup;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminLTEUserGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any adminlteusergroup.
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

        if (isset($menu_permissions['adminlteusergroup']))
        {
            $has_permission = $menu_permissions['adminlteusergroup'];
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can view the adminlteusergroup.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE\AdminLTEUserGroup  $adminlteusergroup
     * @return mixed
     */
    public function view(AdminLTEUser $user, AdminLTEUserGroup $adminlteusergroup)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectAdminLTE = new AdminLTE();
        $model_permissions = $objectAdminLTE->getUserModelPermissions();

        if (isset($model_permissions['adminlteusergroup']))
        {
            $tokens = explode(',', $model_permissions['adminlteusergroup']);
            $has_permission = in_array('read', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can create adminlteusergroup.
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

        if (isset($model_permissions['adminlteusergroup']))
        {
            $tokens = explode(',', $model_permissions['adminlteusergroup']);
            $has_permission = in_array('create', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can update the adminlteusergroup.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE\AdminLTEUserGroup  $adminlteusergroup
     * @return mixed
     */
    public function update(AdminLTEUser $user, AdminLTEUserGroup $adminlteusergroup)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectAdminLTE = new AdminLTE();
        $model_permissions = $objectAdminLTE->getUserModelPermissions();

        if (isset($model_permissions['adminlteusergroup']))
        {
            $tokens = explode(',', $model_permissions['adminlteusergroup']);
            $has_permission = in_array('update', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can delete the adminlteusergroup.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE\AdminLTEUserGroup  $adminlteusergroup
     * @return mixed
     */
    public function delete(AdminLTEUser $user, AdminLTEUserGroup $adminlteusergroup)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }
        
        $has_permission = false;
        
        $objectAdminLTE = new AdminLTE();
        $model_permissions = $objectAdminLTE->getUserModelPermissions();

        if (isset($model_permissions['adminlteusergroup']))
        {
            $tokens = explode(',', $model_permissions['adminlteusergroup']);
            $has_permission = in_array('delete', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can restore the adminlteusergroup.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE\AdminLTEUserGroup  $adminlteusergroup
     * @return mixed
     */
    public function restore(AdminLTEUser $user, AdminLTEUserGroup $adminlteusergroup)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the adminlteusergroup.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE\AdminLTEUserGroup  $adminlteusergroup
     * @return mixed
     */
    public function forceDelete(AdminLTEUser $user, AdminLTEUserGroup $adminlteusergroup)
    {
        //
    }
}
