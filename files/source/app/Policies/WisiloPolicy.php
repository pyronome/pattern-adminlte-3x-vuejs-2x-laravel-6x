<?php

namespace App\Policies;

use App\Wisilo\WisiloUser;
use App\Wisilo;
use Illuminate\Auth\Access\HandlesAuthorization;

class WisiloPolicy
{
    use HandlesAuthorization;

    public function has_menu_permission(WisiloUser $user, $permission_key) // $permission_key = 'home' 
    {
        $menu_permissions = $objectWisilo->getUserMenuPermissions();

        if (isset($menu_permissions[$permission_key]))
        {
            $has_permission = $menu_permissions[$permission_key];
        }

        return $has_permission ? $this->allow(true) : $this->deny(false);
    }
    
    /**
     * Determine whether the user can view any wisilo.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @return mixed
     */
    public function viewAny(WisiloUser $user)
    {
        //
    }

    /**
     * Determine whether the user can view the wisilo.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo  $wisilo
     * @return mixed
     */
    public function view(WisiloUser $user, Wisilo $wisilo)
    {
        //
    }

    /**
     * Determine whether the user can create wisilo.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @return mixed
     */
    public function create(WisiloUser $user)
    {
        //
    }

    /**
     * Determine whether the user can update the wisilo.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo  $wisilo
     * @return mixed
     */
    public function update(WisiloUser $user, Wisilo $wisilo)
    {
        //
    }

    /**
     * Determine whether the user can delete the wisilo.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo  $wisilo
     * @return mixed
     */
    public function delete(WisiloUser $user, Wisilo $wisilo)
    {
        //
    }

    /**
     * Determine whether the user can restore the wisilo.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo  $wisilo
     * @return mixed
     */
    public function restore(WisiloUser $user, Wisilo $wisilo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the wisilo.
     *
     * @param  \App\Wisilo\WisiloUser  $user
     * @param  \App\Wisilo  $wisilo
     * @return mixed
     */
    public function forceDelete(WisiloUser $user, Wisilo $wisilo)
    {
        //
    }
}
