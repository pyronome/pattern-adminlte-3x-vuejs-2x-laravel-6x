<?php

namespace App\Policies;

use Illuminate\Support\Facades\Gate;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;
use App\Wisilo\WisiloMedia;
use Illuminate\Auth\Access\HandlesAuthorization;

class WisiloMediaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any wisilomedia.
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

        if (isset($menu_permissions['wisilomedia']))
        {
            $has_permission = $menu_permissions['wisilomedia'];
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can view the wisilomedia.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\WisiloMedia  $wisilomedia
     * @return mixed
     */
    public function view(WisiloUser $user, WisiloMedia $wisilomedia)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectWisilo = new Wisilo();
        $model_permissions = $objectWisilo->getUserModelPermissions();

        if (isset($model_permissions['wisilomedia']))
        {
            $tokens = explode(',', $model_permissions['wisilomedia']);
            $has_permission = in_array('read', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can create wisilomedia.
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

        if (isset($model_permissions['wisilomedia']))
        {
            $tokens = explode(',', $model_permissions['wisilomedia']);
            $has_permission = in_array('create', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can update the wisilomedia.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\WisiloMedia  $wisilomedia
     * @return mixed
     */
    public function update(WisiloUser $user, WisiloMedia $wisilomedia)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }

        $has_permission = false;
        
        $objectWisilo = new Wisilo();
        $model_permissions = $objectWisilo->getUserModelPermissions();

        if (isset($model_permissions['wisilomedia']))
        {
            $tokens = explode(',', $model_permissions['wisilomedia']);
            $has_permission = in_array('update', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can delete the wisilomedia.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\WisiloMedia  $wisilomedia
     * @return mixed
     */
    public function delete(WisiloUser $user, WisiloMedia $wisilomedia)
    {
        if (Gate::allows('isAdmin')) {
            return true;
        }
        
        $has_permission = false;
        
        $objectWisilo = new Wisilo();
        $model_permissions = $objectWisilo->getUserModelPermissions();

        if (isset($model_permissions['wisilomedia']))
        {
            $tokens = explode(',', $model_permissions['wisilomedia']);
            $has_permission = in_array('delete', $tokens);
        }

        return $has_permission;
    }

    /**
     * Determine whether the user can restore the wisilomedia.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\WisiloMedia  $wisilomedia
     * @return mixed
     */
    public function restore(WisiloUser $user, WisiloMedia $wisilomedia)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the wisilomedia.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\WisiloMedia  $wisilomedia
     * @return mixed
     */
    public function forceDelete(WisiloUser $user, WisiloMedia $wisilomedia)
    {
        //
    }
}