<?php

namespace App\Policies;

use App\AdminLTE\AdminLTEUser;
use App\AdminLTE;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminLTEPolicy
{
    use HandlesAuthorization;

    public function has_menu_permission(AdminLTEUser $user, $permission_key) // $permission_key = 'home' olsun
    {
        $permissions = json_decode($user->permission, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));

        $menu_permission_object = $permissions['menu']; // meta_key == 'menu' olan..
        $menu_permissions = $menu_permission_object->permissions;

        return (1 == $menu_permissions->$permission_key) ? $this->allow(true) : $this->deny(false);
    }
    
    /**
     * Determine whether the user can view any adminLTE.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @return mixed
     */
    public function viewAny(AdminLTEUser $user)
    {
        //
    }

    /**
     * Determine whether the user can view the adminLTE.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE  $adminLTE
     * @return mixed
     */
    public function view(AdminLTEUser $user, AdminLTE $adminLTE)
    {
        //
    }

    /**
     * Determine whether the user can create adminLTE.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @return mixed
     */
    public function create(AdminLTEUser $user)
    {
        //
    }

    /**
     * Determine whether the user can update the adminLTE.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE  $adminLTE
     * @return mixed
     */
    public function update(AdminLTEUser $user, AdminLTE $adminLTE)
    {
        //
    }

    /**
     * Determine whether the user can delete the adminLTE.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE  $adminLTE
     * @return mixed
     */
    public function delete(AdminLTEUser $user, AdminLTE $adminLTE)
    {
        //
    }

    /**
     * Determine whether the user can restore the adminLTE.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE  $adminLTE
     * @return mixed
     */
    public function restore(AdminLTEUser $user, AdminLTE $adminLTE)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the adminLTE.
     *
     * @param  \App\AdminLTE\AdminLTEUser  $user
     * @param  \App\AdminLTE  $adminLTE
     * @return mixed
     */
    public function forceDelete(AdminLTEUser $user, AdminLTE $adminLTE)
    {
        //
    }
}
