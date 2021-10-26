<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\Http\Requests\AdminLTE\API\MenuConfigurationPOSTRequest;

class MenuConfigurationController extends Controller
{

    public function get(Request $request)
    {
        $response = [];

        $AdminLTE = new AdminLTE();
        $menu = $AdminLTE->getAdminLTEMenu();

        $response['menu_json'] = json_encode($menu,
                (JSON_HEX_QUOT
                | JSON_HEX_TAG
                | JSON_HEX_AMP
                | JSON_HEX_APOS));
        
        return $response;
    }

    public function post(MenuConfigurationPOSTRequest $request)
    {
        $has_error = false;
        $error_msg = '';
        $return_data = [];

        $menu_json = rawurldecode(htmlspecialchars_decode($request->input('menu_json')));
        $menu = json_decode($menu_json,
                (JSON_HEX_QUOT
                | JSON_HEX_TAG
                | JSON_HEX_AMP
                | JSON_HEX_APOS));

        $AdminLTE = new AdminLTE();
        $AdminLTE->saveAdminLTEMenu($menu);
        
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

}
