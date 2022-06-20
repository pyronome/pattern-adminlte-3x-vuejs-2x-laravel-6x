<?php

namespace App\Http\Controllers\Wisilo\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUserLayout;
use App\Http\Requests\Wisilo\API\PreferencesPOSTRequest;

class PreferencesController extends Controller
{
    public function get(Request $request)
    {    
        $objectWisilo = new Wisilo();
        $preferences = $objectWisilo->getUserPreferences();

        return [
            'preferences' => $preferences
        ];
    }

    public function post(PreferencesPOSTRequest $request)
    {
        $objectWisilo = new Wisilo();

        $preferences = $request->input('preferences');
        $preferencesJSON = $objectWisilo->base64encode(json_encode($preferences, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS)));

        if ('' == $preferencesJSON) {
            $preferencesJSON = '[]';
        } 

        $user_data = $objectWisilo->getUserData();

        $objectWisiloUserLayout = null;
        $objectWisiloUserLayouts = WisiloUserLayout::where('deleted', false)->where('wisilouser_id', $user_data['id'])->where('pagename', 'preferences')->get();

        if (count($objectWisiloUserLayouts) > 0) {
            $objectWisiloUserLayout = $objectWisiloUserLayouts[0];
        } else {
            $objectWisiloUserLayout = new WisiloUserLayout();
        }
        
        $objectWisiloUserLayout->wisilouser_id = $user_data['id'];
        $objectWisiloUserLayout->pagename = 'preferences';
        $objectWisiloUserLayout->widgets = $preferencesJSON;
        $objectWisiloUserLayout->save();

        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }
}