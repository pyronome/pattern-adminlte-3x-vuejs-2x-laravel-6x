<?php

namespace App\Observers;

use App\Wisilo\WisiloLog;
use App\Wisilo\WisiloMedia;

class WisiloMediaObserver
{
    /**
     * Handle the object "created" event.
     *
     * @param  \App\WisiloMedia  $object
     * @return void
     */
    public function created(WisiloMedia $object)
    {
        $objectLog = new WisiloLog();
        $objectLog->user_id = $object->created_by;
        $objectLog->type = 'INSERT';
        $objectLog->title = 'WisiloMedia';
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
     * @param  \App\WisiloMedia  $object
     * @return void
     */
    public function updating(WisiloMedia $object)
    {
        if (1 == $object->deleted) {
            $objectLog = new WisiloLog();
            $objectLog->user_id = $object->updated_by;
            $objectLog->type = 'DELETE';
            $objectLog->title = 'WisiloMedia';
            $objectLog->sub_title = '';
            $objectLog->object_id = $object->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($object);
            $objectLog->object_new_values = '';
            $objectLog->message = '';
            $objectLog->save();
        } else {
            $objectLog = new WisiloLog();
            $objectLog->user_id = $object->updated_by;
            $objectLog->type = 'UPDATE';
            $objectLog->title = 'WisiloMedia';
            $objectLog->sub_title = '';
            $objectLog->object_id = $object->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($object);
            $objectLog->object_new_values = $objectLog->getObjectNewValues($object);
            $objectLog->message = '';
            $objectLog->save();
        }
    }
}
