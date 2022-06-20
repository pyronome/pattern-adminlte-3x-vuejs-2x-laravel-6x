<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;
use App\Wisilo\WisiloConfig;
use Illuminate\Auth\Access\HandlesAuthorization;

class WisiloConfigPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any wisiloconfig.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @return mixed
     */
    public function viewAny(WisiloUser $user, WisiloConfig $wisiloconfig)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        if (1 == $wisiloconfig->only_admins) {
            return false;
        }

        $has_permission = false;

        $objectWisilo = new Wisilo();
        $menu_permissions = $objectWisilo->getUserMenuPermissions();

        if (isset($menu_permissions['configuration']))
        {
            $has_permission = $menu_permissions['configuration'];
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can view the wisiloconfig.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo\WisiloConfig  $wisiloconfig
     * @return mixed
     */
    public function view(WisiloUser $user, WisiloConfig $wisiloconfig)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectWisilo = new Wisilo();
        $model_permissions = $objectWisilo->getUserModelPermissions();

        if (isset($model_permissions['wisiloconfig']))
        {
            $tokens = explode(',', $model_permissions['wisiloconfig']);
            $has_permission = in_array('read', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can create wisiloconfig.
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

        if (isset($model_permissions['wisiloconfig']))
        {
            $tokens = explode(',', $model_permissions['wisiloconfig']);
            $has_permission = in_array('create', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can update the wisiloconfig.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo\WisiloConfig  $wisiloconfig
     * @return mixed
     */
    public function update(WisiloUser $user, WisiloConfig $wisiloconfig)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectWisilo = new Wisilo();
        $model_permissions = $objectWisilo->getUserModelPermissions();

        if (isset($model_permissions['wisiloconfig']))
        {
            $tokens = explode(',', $model_permissions['wisiloconfig']);
            $has_permission = in_array('update', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can delete the wisiloconfig.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo\WisiloConfig  $wisiloconfig
     * @return mixed
     */
    public function delete(WisiloUser $user, WisiloConfig $wisiloconfig)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }
        
        $has_permission = false;
        
        $objectWisilo = new Wisilo();
        $model_permissions = $objectWisilo->getUserModelPermissions();

        if (isset($model_permissions['wisiloconfig']))
        {
            $tokens = explode(',', $model_permissions['wisiloconfig']);
            $has_permission = in_array('delete', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can restore the wisiloconfig.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo\WisiloConfig  $wisiloconfig
     * @return mixed
     */
    public function restore(WisiloUser $user, WisiloConfig $wisiloconfig)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the wisiloconfig.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo\WisiloConfig  $wisiloconfig
     * @return mixed
     */
    public function forceDelete(WisiloUser $user, WisiloConfig $wisiloconfig)
    {
        //
    }
}