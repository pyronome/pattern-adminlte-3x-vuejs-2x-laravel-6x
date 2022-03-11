<?php

namespace App\Observers;

use App\AdminLTE\AdminLTELog;
use App\AdminLTE\AdminLTECustomVariable;

class AdminLTECustomVariableObserver
{
    /**
     * Handle the adminltecustomvariable "created" event.
     *
     * @param  \App\AdminLTECustomVariable  $adminltecustomvariable
     * @return void
     */
    public function created(AdminLTECustomVariable $adminltecustomvariable)
    {
        $objectLog = new AdminLTELog();
        $objectLog->user_id = $adminltecustomvariable->created_by;
        $objectLog->type = 'INSERT';
        $objectLog->title = 'AdminLTECustomVariable';
        $objectLog->sub_title = '';
        $objectLog->object_id = $adminltecustomvariable->id;
        $objectLog->object_old_values = '';
        $objectLog->object_new_values = $objectLog->getObjectNewValues($adminltecustomvariable);
        $objectLog->message = '';
        $objectLog->save();
    }

    /**
     * Handle the adminltecustomvariable "updating" event.
     *
     * @param  \App\AdminLTECustomVariable  $adminltecustomvariable
     * @return void
     */
    public function updating(AdminLTECustomVariable $adminltecustomvariable)
    {
        if (1 == $adminltecustomvariable->deleted) {
            $objectLog = new AdminLTELog();
            $objectLog->user_id = $adminltecustomvariable->updated_by;
            $objectLog->type = 'DELETE';
            $objectLog->title = 'AdminLTECustomVariable';
            $objectLog->sub_title = '';
            $objectLog->object_id = $adminltecustomvariable->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($adminltecustomvariable);
            $objectLog->object_new_values = '';
            $objectLog->message = '';
            $objectLog->save();
        } else {
            $objectLog = new AdminLTELog();
            $objectLog->user_id = $adminltecustomvariable->updated_by;
            $objectLog->type = 'UPDATE';
            $objectLog->title = 'AdminLTECustomVariable';
            $objectLog->sub_title = '';
            $objectLog->object_id = $adminltecustomvariable->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($adminltecustomvariable);
            $objectLog->object_new_values = $objectLog->getObjectNewValues($adminltecustomvariable);
            $objectLog->message = '';
            $objectLog->save();
        }
    }
}
