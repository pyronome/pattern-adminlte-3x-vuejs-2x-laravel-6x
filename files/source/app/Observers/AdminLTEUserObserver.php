<?php

namespace App\Observers;

use App\AdminLTE\AdminLTELog;
use App\AdminLTEUser;

class AdminLTEUserObserver
{
    /**
     * Handle the adminlteuser "created" event.
     *
     * @param  \App\AdminLTEUser  $adminlteuser
     * @return void
     */
    public function created(AdminLTEUser $adminlteuser)
    {
        $objectLog = new AdminLTELog();
        $objectLog->user_id = $adminlteuser->created_by;
        $objectLog->type = 'INSERT';
        $objectLog->title = 'AdminLTEUser';
        $objectLog->sub_title = '';
        $objectLog->object_id = $adminlteuser->id;
        $objectLog->object_old_values = '';
        $objectLog->object_new_values = $objectLog->getObjectNewValues($adminlteuser);
        $objectLog->message = '';
        $objectLog->save();
    }

    /**
     * Handle the adminlteuser "updating" event.
     *
     * @param  \App\AdminLTEUser  $adminlteuser
     * @return void
     */
    public function updating(AdminLTEUser $adminlteuser)
    {
        if (1 == $adminlteuser->deleted) {
            $objectLog = new AdminLTELog();
            $objectLog->user_id = $adminlteuser->created_by;
            $objectLog->type = 'DELETE';
            $objectLog->title = 'AdminLTEUser';
            $objectLog->sub_title = '';
            $objectLog->object_id = $adminlteuser->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($adminlteuser);
            $objectLog->object_new_values = '';
            $objectLog->message = '';
            $objectLog->save();
        } else {
            $objectLog = new AdminLTELog();
            $objectLog->user_id = $adminlteuser->created_by;
            $objectLog->type = 'UPDATE';
            $objectLog->title = 'AdminLTEUser';
            $objectLog->sub_title = '';
            $objectLog->object_id = $adminlteuser->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($adminlteuser);
            $objectLog->object_new_values = $objectLog->getObjectNewValues($adminlteuser);
            $objectLog->message = '';
            $objectLog->save();
        }
    }
}
