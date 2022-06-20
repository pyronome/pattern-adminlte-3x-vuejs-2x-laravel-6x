<?php

namespace App\Observers;

use App\Wisilo\WisiloLog;
use App\Wisilo\WisiloUser;

class WisiloUserObserver
{
    /**
     * Handle the wisilouser "created" event.
     *
     * @param  \App\WisiloUser  $wisilouser
     * @return void
     */
    public function created(WisiloUser $wisilouser)
    {
        $objectLog = new WisiloLog();
        $objectLog->user_id = $wisilouser->created_by;
        $objectLog->type = 'INSERT';
        $objectLog->title = 'WisiloUser';
        $objectLog->sub_title = '';
        $objectLog->object_id = $wisilouser->id;
        $objectLog->object_old_values = '';
        $objectLog->object_new_values = $objectLog->getObjectNewValues($wisilouser);
        $objectLog->message = '';
        $objectLog->save();
    }

    /**
     * Handle the wisilouser "updating" event.
     *
     * @param  \App\WisiloUser  $wisilouser
     * @return void
     */
    public function updating(WisiloUser $wisilouser)
    {
        if (1 == $wisilouser->deleted) {
            $objectLog = new WisiloLog();
            $objectLog->user_id = $wisilouser->updated_by;
            $objectLog->type = 'DELETE';
            $objectLog->title = 'WisiloUser';
            $objectLog->sub_title = '';
            $objectLog->object_id = $wisilouser->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($wisilouser);
            $objectLog->object_new_values = '';
            $objectLog->message = '';
            $objectLog->save();
        } else {
            $objectLog = new WisiloLog();
            $objectLog->user_id = $wisilouser->updated_by;
            $objectLog->type = 'UPDATE';
            $objectLog->title = 'WisiloUser';
            $objectLog->sub_title = '';
            $objectLog->object_id = $wisilouser->id;
            $objectLog->object_old_values = $objectLog->getObjectStoredValues($wisilouser);
            $objectLog->object_new_values = $objectLog->getObjectNewValues($wisilouser);
            $objectLog->message = '';
            $objectLog->save();
        }
    }
}
