<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;
use App\Wisilo\WisiloUserGroup;
use Illuminate\Auth\Access\HandlesAuthorization;

class WisiloUserGroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any wisilousergroup.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @return mixed
     */
    public function viewAny(WisiloUser $user)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectWisilo = new Wisilo();
        $menu_permissions = $objectWisilo->getUserMenuPermissions();

        if (isset($menu_permissions['wisilousergroup']))
        {
            $has_permission = $menu_permissions['wisilousergroup'];
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can view the wisilousergroup.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo\WisiloUserGroup  $wisilousergroup
     * @return mixed
     */
    public function view(WisiloUser $user, WisiloUserGroup $wisilousergroup)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectWisilo = new Wisilo();
        $model_permissions = $objectWisilo->getUserModelPermissions();

        if (isset($model_permissions['wisilousergroup']))
        {
            $tokens = explode(',', $model_permissions['wisilousergroup']);
            $has_permission = in_array('read', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can create wisilousergroup.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @return mixed
     */
    public function create(WisiloUser $user)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectWisilo = new Wisilo();
        $model_permissions = $objectWisilo->getUserModelPermissions();

        if (isset($model_permissions['wisilousergroup']))
        {
            $tokens = explode(',', $model_permissions['wisilousergroup']);
            $has_permission = in_array('create', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can update the wisilousergroup.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo\WisiloUserGroup  $wisilousergroup
     * @return mixed
     */
    public function update(WisiloUser $user, WisiloUserGroup $wisilousergroup)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectWisilo = new Wisilo();
        $model_permissions = $objectWisilo->getUserModelPermissions();

        if (isset($model_permissions['wisilousergroup']))
        {
            $tokens = explode(',', $model_permissions['wisilousergroup']);
            $has_permission = in_array('update', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can delete the wisilousergroup.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo\WisiloUserGroup  $wisilousergroup
     * @return mixed
     */
    public function delete(WisiloUser $user, WisiloUserGroup $wisilousergroup)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }
        
        $has_permission = false;
        
        $objectWisilo = new Wisilo();
        $model_permissions = $objectWisilo->getUserModelPermissions();

        if (isset($model_permissions['wisilousergroup']))
        {
            $tokens = explode(',', $model_permissions['wisilousergroup']);
            $has_permission = in_array('delete', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can restore the wisilousergroup.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo\WisiloUserGroup  $wisilousergroup
     * @return mixed
     */
    public function restore(WisiloUser $user, WisiloUserGroup $wisilousergroup)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the wisilousergroup.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo\WisiloUserGroup  $wisilousergroup
     * @return mixed
     */
    public function forceDelete(WisiloUser $user, WisiloUserGroup $wisilousergroup)
    {
        //
    }
}
