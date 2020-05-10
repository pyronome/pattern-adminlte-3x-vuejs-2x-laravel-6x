<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\AdminLTE;
use App\AdminLTEUser;
use App\Http\Requests\AdminLTE\API\MenuConfigurationPOSTRequest;

class MenuConfigurationController extends Controller
{

    public function get(Request $request)
    {

        $response = [];

        if (Storage::disk('local')->exists('adminlte_menu.json'))
		{
			$menu_json = Storage::disk('local')->get('adminlte_menu.json');
		}
		else
		{
			$menu_json = config('adminlte_menu_json');
		} // if (!$forceDefault

        $response['menu_json'] = json_encode($menu_json,
                (JSON_HEX_QUOT
                | JSON_HEX_TAG
                | JSON_HEX_AMP
                | JSON_HEX_APOS));
        
        return $response;

    }

    public function post(MenuConfigurationPOSTRequest $request)
    {

        $menu_json = rawurldecode(
                htmlspecialchars_decode(
                $request->input('menu_json')));

        Storage::disk('local')->put(
                'adminlte_menu.json',
                $menu_json);
        
        return ['message' => 'UPDATED'];

    }

}
