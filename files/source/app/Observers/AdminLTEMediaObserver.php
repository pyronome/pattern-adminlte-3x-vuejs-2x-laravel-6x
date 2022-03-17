<?php

namespace App\Observers;

use App\AdminLTE\AdminLTELog;
use App\AdminLTE\AdminLTEMedia;

class AdminLTEMediaObserver
{
    /**
     * Handle the object "created" event.
     *
     * @param  \App\AdminLTEMedia  $object
     * @return void
     */
    public function created(AdminLTEMedia $object)
    {
        $objectLog = new AdminLTELog();
        $objectLog->user_id = $object->created_by;
        $objectLog->type = 'INSERT';
        $objectLog->title = 'AdminLTEMedia';
        $objectLog->sub_title = '';
        $objectLog->object_id = $object->id;
        $objectLog->object_old_values = '';
        $objectLog->object_new_values = $objectLog->getObjectNewValues($object);
        $objectLog->message = '';
        $objectLog->save();
    }

    /**
     * Handle the object "updating" event.
     *
     * @param  \App\AdminLTEMedia  $object
     * @return void
     */
    public function updating(AdminLTEMedia $object)
    {
        if (1 == $object->deleted) {
            $objectLog = new AdminLTELog();
            $objectLog->user_id = $object->updated_by;
            $objectLog->type = 'DELETE';
            $objectLog->title = 'AdminLTEMedia';
            $objectLog->sub_title = '';
            $objectLog->object_id = $object->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($object);
            $objectLog->object_new_values = '';
            $objectLog->message = '';
            $objectLog->save();
        } else {
            $objectLog = new AdminLTELog();
            $objectLog->user_id = $object->updated_by;
            $objectLog->type = 'UPDATE';
            $objectLog->title = 'AdminLTEMedia';
            $objectLog->sub_title = '';
            $objectLog->object_id = $object->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($object);
            $objectLog->object_new_values = $objectLog->getObjectNewValues($object);
            $objectLog->message = '';
            $objectLog->save();
        }
    }
}
