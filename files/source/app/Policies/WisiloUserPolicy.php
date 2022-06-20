<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class WisiloUserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any wisilouser.
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

        if (isset($menu_permissions['wisilouser']))
        {
            $has_permission = $menu_permissions['wisilouser'];
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can view the wisilouser.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo\WisiloUser  $wisilouser
     * @return mixed
     */
    public function view(WisiloUser $user, WisiloUser $wisilouser)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectWisilo = new Wisilo();
        $model_permissions = $objectWisilo->getUserModelPermissions();

        if (isset($model_permissions['wisilouser']))
        {
            $tokens = explode(',', $model_permissions['wisilouser']);
            $has_permission = in_array('read', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can create wisilouser.
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

        if (isset($model_permissions['wisilouser']))
        {
            $tokens = explode(',', $model_permissions['wisilouser']);
            $has_permission = in_array('create', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can update the wisilouser.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo\WisiloUser  $wisilouser
     * @return mixed
     */
    public function update(WisiloUser $user, WisiloUser $wisilouser)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectWisilo = new Wisilo();
        $model_permissions = $objectWisilo->getUserModelPermissions();

        if (isset($model_permissions['wisilouser']))
        {
            $tokens = explode(',', $model_permissions['wisilouser']);
            $has_permission = in_array('update', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can delete the wisilouser.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo\WisiloUser  $wisilouser
     * @return mixed
     */
    public function delete(WisiloUser $user, WisiloUser $wisilouser)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }
        
        $has_permission = false;
        
        $objectWisilo = new Wisilo();
        $model_permissions = $objectWisilo->getUserModelPermissions();

        if (isset($model_permissions['wisilouser']))
        {
            $tokens = explode(',', $model_permissions['wisilouser']);
            $has_permission = in_array('delete', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can restore the wisilouser.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo\WisiloUser  $wisilouser
     * @return mixed
     */
    public function restore(WisiloUser $user, WisiloUser $wisilouser)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the wisilouser.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo\WisiloUser  $wisilouser
     * @return mixed
     */
    public function forceDelete(WisiloUser $user, WisiloUser $wisilouser)
    {
        //
    }
}
