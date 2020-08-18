<?php

namespace App\Http\Controllers\AdminLTE\HTMLDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE\AdminLTEUserLayout;
use App\AdminLTE\AdminLTE;
use App\HTMLDB\HTMLDB;

class PreferencesController extends Controller
{
    public $columns = ['id', 'preferences_json'];
    public $protectedColumns = [];
    public $row = [];

    public function check()
    {
        $result = [
            'lastError' => '',
            'errorCount' => 0,
            'lastMessage' => '',
            'messageCount' => 0
        ];

        /* {{@snippet:begin_check_values}} */

        /* {{@snippet:end_check_values}} */

        return $result;
    }
    
    function get(Request $request)
    {
        // start: check user get permission
        /*
        ::must_update:: servis izinleri nasıl kontrol ediliyor ?

        $directoryName = basename(dirname(__FILE__));
        $fileName = basename(__FILE__);
    
        includeLibrary('adminlte/checkUserGetPermission');
        $permissionResult = checkUserGetPermission($directoryName, $fileName);
    
        if ($permissionResult['error']) {
            $controller->errorCount = 1;
            $controller->lastError = $permissionResult['error_msg'];
            return false;
        }*/
        // end: check user get permission
          
        $list = [];
        $list[0]['id'] = 1;

        $objectAdminLTE = new AdminLTE();

        $list[0]['preferences_json'] = json_encode($objectAdminLTE->getUserPreferences(), (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }
    
    public function post(Request $request)
    {
        /* ::must_update:: servis izinleri nasıl kontrol ediliyor ?
        // start: check user post permission
        $directoryName = basename(dirname(__FILE__));
        $fileName = basename(__FILE__);

        includeLibrary('adminlte/checkUserPostPermission');
        $permissionResult = checkUserPostPermission($directoryName, $fileName);

        if ($permissionResult['error']) {
            $controller->errorCount = 1;
            $controller->lastError = $permissionResult['error_msg'];
            return false;
        }
        // end: check user post permission
        */

        $objectHTMLDB = new HTMLDB();

        $this->row = $objectHTMLDB->requestPOSTRow(
                $request->all(),
                $this->columns,
                $this->protectedColumns,
                0,
                false);
        
        $result = $this->check();

        if (0 == $result['errorCount'])
        {
            $preferences_json = html_entity_decode(htmlspecialchars($this->row['preferences_json']));

            if ('' == $preferences_json) {
                $preferences_json = '[]';
            } 
            
            $objectAdminLTE = new AdminLTE();

            $preferences = $objectAdminLTE->base64encode($preferences_json);

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
            $objectAdminLTEUserLayout->widgets = $preferences;
            $objectAdminLTEUserLayout->save();

            $result['messageCount'] = 1;
            $result['lastMessage'] = 'UPDATED';
        }

        $objectHTMLDB->lastError = $result['lastError'];
        $objectHTMLDB->errorCount = $result['errorCount'];
        $objectHTMLDB->lastMessage = $result['lastMessage'];
        $objectHTMLDB->messageCount = $result['messageCount'];

        $objectHTMLDB->printResponseJSON();
        return;
    }
}
