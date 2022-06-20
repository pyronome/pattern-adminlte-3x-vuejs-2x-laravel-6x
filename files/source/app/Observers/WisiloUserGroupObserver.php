<?php

namespace App\Observers;

use App\Wisilo\WisiloLog;
use App\Wisilo\WisiloUserGroup;

class WisiloUserGroupObserver
{
    /**
     * Handle the wisilousergroup "created" event.
     *
     * @param  \App\WisiloUserGroup  $wisilousergroup
     * @return void
     */
    public function created(WisiloUserGroup $wisilousergroup)
    {
        $objectLog = new WisiloLog();
        $objectLog->user_id = $wisilousergroup->created_by;
        $objectLog->type = 'INSERT';
        $objectLog->title = 'WisiloUserGroup';
        $objectLog->sub_title = '';
        $objectLog->object_id = $wisilousergroup->id;
        $objectLog->object_old_values = '';
        $objectLog->object_new_values = $objectLog->getObjectNewValues($wisilousergroup);
        $objectLog->message = '';
        $objectLog->save();
    }

    /**
     * Handle the wisilousergroup "updating" event.
     *
     * @param  \App\WisiloUserGroup  $wisilousergroup
     * @return void
     */
    public function updating(WisiloUserGroup $wisilousergroup)
    {
        if (1 == $wisilousergroup->deleted) {
            $objectLog = new WisiloLog();
            $objectLog->user_id = $wisilousergroup->updated_by;
            $objectLog->type = 'DELETE';
            $objectLog->title = 'WisiloUserGroup';
            $objectLog->sub_title = '';
            $objectLog->object_id = $wisilousergroup->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($wisilousergroup);
            $objectLog->object_new_values = '';
            $objectLog->message = '';
            $objectLog->save();
        } else {
            $objectLog = new WisiloLog();
            $objectLog->user_id = $wisilousergroup->updated_by;
            $objectLog->type = 'UPDATE';
            $objectLog->title = 'WisiloUserGroup';
            $objectLog->sub_title = '';
            $objectLog->object_id = $wisilousergroup->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($wisilousergroup);
            $objectLog->object_new_values = $objectLog->getObjectNewValues($wisilousergroup);
            $objectLog->message = '';
            $objectLog->save();
        }
    }
}
