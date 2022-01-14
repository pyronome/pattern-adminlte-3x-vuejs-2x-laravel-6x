<?php

namespace App\Observers;

use App\AdminLTE\AdminLTELog;
use App\AdminLTE\AdminLTEUserGroup;

class AdminLTEUserGroupObserver
{
    /**
     * Handle the adminlteusergroup "created" event.
     *
     * @param  \App\AdminLTEUserGroup  $adminlteusergroup
     * @return void
     */
    public function created(AdminLTEUserGroup $adminlteusergroup)
    {
        $objectLog = new AdminLTELog();
        $objectLog->user_id = $adminlteusergroup->created_by;
        $objectLog->type = 'INSERT';
        $objectLog->title = 'AdminLTEUserGroup';
        $objectLog->sub_title = '';
        $objectLog->object_id = $adminlteusergroup->id;
        $objectLog->object_old_values = '';
        $objectLog->object_new_values = $objectLog->getObjectNewValues($adminlteusergroup);
        $objectLog->message = '';
        $objectLog->save();
    }

    /**
     * Handle the adminlteusergroup "updating" event.
     *
     * @param  \App\AdminLTEUserGroup  $adminlteusergroup
     * @return void
     */
    public function updating(AdminLTEUserGroup $adminlteusergroup)
    {
        if (1 == $adminlteusergroup->deleted) {
            $objectLog = new AdminLTELog();
            $objectLog->user_id = $adminlteusergroup->created_by;
            $objectLog->type = 'DELETE';
            $objectLog->title = 'AdminLTEUserGroup';
            $objectLog->sub_title = '';
            $objectLog->object_id = $adminlteusergroup->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($adminlteusergroup);
            $objectLog->object_new_values = '';
            $objectLog->message = '';
            $objectLog->save();
        } else {
            $objectLog = new AdminLTELog();
            $objectLog->user_id = $adminlteusergroup->created_by;
            $objectLog->type = 'UPDATE';
            $objectLog->title = 'AdminLTEUserGroup';
            $objectLog->sub_title = '';
            $objectLog->object_id = $adminlteusergroup->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($adminlteusergroup);
            $objectLog->object_new_values = $objectLog->getObjectNewValues($adminlteusergroup);
            $objectLog->message = '';
            $objectLog->save();
        }
    }
}
