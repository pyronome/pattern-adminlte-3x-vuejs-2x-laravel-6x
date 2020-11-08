<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUserLayout;
use App\Http\Requests\AdminLTE\API\PreferencesPOSTRequest;

class PreferencesController extends Controller
{
    public function get(Request $request)
    {    
        $objectAdminLTE = new AdminLTE();
        $preferences = $objectAdminLTE->getUserPreferences();

        return [
            'preferences' => $preferences
        ];
    }

    public function post(PreferencesPOSTRequest $request)
    {
        $objectAdminLTE = new AdminLTE();

        $preferences = $request->input('preferences');
        $preferencesJSON = $objectAdminLTE->base64encode(json_encode($preferences, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS)));

        if ('' == $preferencesJSON) {
            $preferencesJSON = '[]';
        } 

        $user_data = $objectAdminLTE->getUserData();

        $objectAdminLTEUserLayout = null;
        $objectAdminLTEUserLayouts = AdminLTEUserLayout::where('deleted', false)->where('adminlteuser_id', $user_data['id'])->where('pagename', 'preferences')->get();

        if (count($objectAdminLTEUserLayouts) > 0) {
            $objectAdminLTEUserLayout = $objectAdminLTEUserLayouts[0];
        } else {
            $objectAdminLTEUserLayout = new AdminLTEUserLayout();
        }
        
        $objectAdminLTEUserLayout->adminlteuser_id = $user_data['id'];
        $objectAdminLTEUserLayout->pagename = 'preferences';
        $objectAdminLTEUserLayout->widgets = $preferencesJSON;
        $objectAdminLTEUserLayout->save();

        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }
}