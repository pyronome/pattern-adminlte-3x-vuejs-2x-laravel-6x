<?php

namespace App\Http\Controllers\AdminLTE\HTMLDB;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\AdminLTE;
use App\AdminLTEUser;
use App\HTMLDB;

class MenuConfigurationController extends Controller
{
    public $columns = [
        'id',
        'menu_json'
    ];
    public $protectedColumns = [];
    public $row = [];

    public function get(Request $request)
    {

        $list = [];

        if (Storage::disk('local')->exists('adminlte_menu.json'))
		{
			$menu_json = Storage::disk('local')->get('adminlte_menu.json');
		}
		else
		{
			$menu_json = config('adminlte_menu_json');
		} // if (!$forceDefault

        $list[0]['id'] = 1;
        $list[0]['menu_json'] = json_encode($menu_json,
                (JSON_HEX_QUOT
                | JSON_HEX_TAG
                | JSON_HEX_AMP
                | JSON_HEX_APOS));

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->list = $list;
        $objectHTMLDB->printHTMLDBList();
        return;

    }

    public function post(Request $request)
    {

        $objectHTMLDB = new HTMLDB();

        $this->row = $objectHTMLDB->requestPOSTRow(
                $request->all(),
                $this->columns,
                $this->protectedColumns,
                0,
                true);

        $result = $this->check();

        if (0 == $result['errorCount'])
        {
            $menu_json = rawurldecode(
                    htmlspecialchars_decode(
                    $this->row['menu_json']));
            Storage::disk('local')->put(
                    'adminlte_menu.json',
                    $menu_json);
        } // if (0 == $result['errorCount'])

        if (0 == $result['errorCount']) {
            $result['messageCount'] = 1;
            $result['lastMessage'] = 'UPDATED';
        } // if (0 == $result['errorCount']) {

        $objectHTMLDB->errorCount = $result['errorCount'];
        $objectHTMLDB->messageCount = $result['messageCount'];
        $objectHTMLDB->lastError = $result['lastError'];
        $objectHTMLDB->lastMessage = $result['lastMessage'];
        $objectHTMLDB->printResponseJSON();
        return;

    }

    public function check()
    {

        $result = [
            'lastError' => '',
            'errorCount' => 0,
            'lastMessage' => '',
            'messageCount' => 0
        ];

        /* {{snippet:begin_check_values}} */

        /* {{snippet:end_check_values}} */

        return $result;

    }

}
