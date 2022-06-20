<?php

namespace App\Observers;

use App\Wisilo\WisiloLog;
use App\Wisilo\WisiloCustomVariable;

class WisiloCustomVariableObserver
{
    /**
     * Handle the wisilocustomvariable "created" event.
     *
     * @param  \App\WisiloCustomVariable  $wisilocustomvariable
     * @return void
     */
    public function created(WisiloCustomVariable $wisilocustomvariable)
    {
        $objectLog = new WisiloLog();
        $objectLog->user_id = $wisilocustomvariable->created_by;
        $objectLog->type = 'INSERT';
        $objectLog->title = 'WisiloCustomVariable';
        $objectLog->sub_title = '';
        $objectLog->object_id = $wisilocustomvariable->id;
        $objectLog->object_old_values = '';
        $objectLog->object_new_values = $objectLog->getObjectNewValues($wisilocustomvariable);
        $objectLog->message = '';
        $objectLog->save();
    }

    /**
     * Handle the wisilocustomvariable "updating" event.
     *
     * @param  \App\WisiloCustomVariable  $wisilocustomvariable
     * @return void
     */
    public function updating(WisiloCustomVariable $wisilocustomvariable)
    {
        if (1 == $wisilocustomvariable->deleted) {
            $objectLog = new WisiloLog();
            $objectLog->user_id = $wisilocustomvariable->updated_by;
            $objectLog->type = 'DELETE';
            $objectLog->title = 'WisiloCustomVariable';
            $objectLog->sub_title = '';
            $objectLog->object_id = $wisilocustomvariable->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($wisilocustomvariable);
            $objectLog->object_new_values = '';
            $objectLog->message = '';
            $objectLog->save();
        } else {
            $objectLog = new WisiloLog();
            $objectLog->user_id = $wisilocustomvariable->updated_by;
            $objectLog->type = 'UPDATE';
            $objectLog->title = 'WisiloCustomVariable';
            $objectLog->sub_title = '';
            $objectLog->object_id = $wisilocustomvariable->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($wisilocustomvariable);
            $objectLog->object_new_values = $objectLog->getObjectNewValues($wisilocustomvariable);
            $objectLog->message = '';
            $objectLog->save();
        }
    }
}
