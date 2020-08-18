<?php

namespace App\Http\Controllers\AdminLTE\HTMLDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUserLayout;
use App\HTMLDB\HTMLDB;

class AdminLTELayoutController extends Controller
{

    public $columns = [
        'id',
        'widget_json'
    ];
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
        
        /*if (0 == $controller->user_data['widget_permission']) {
            $controller->errorCount++;

            if ($controller->lastError != '') {
                $controller->lastError .= '<br>';
            } // if ($controller->lastError != '') {

            $controller->lastError .= __('You have not permission to edit widgets.');
            
            return false;
        } // if (0 == $controller->user_data['widget_permission']) {*/

        /* {{@snippet:end_check_values}} */

        return $result;
    }

    public function get_attributes(Request $request)
    {

        $columns = [
            'id',
            'model',
            'attribute'
        ];

        $list = array();

        $objectAdminLTE = new AdminLTE();

        $Models = $objectAdminLTE->getModelList();

        $countModels = count($Models);
        $index = 0;

        for ($i=0; $i < $countModels; $i++)
        {
            $model = $Models[$i];

            $modelNameWithNamespace = ('\\App\\AdminLTE\\' . $model);

            if (!class_exists($modelNameWithNamespace)) {
                $modelNameWithNamespace = ('\\App\\' . $model);
            }
           
            $object = new $modelNameWithNamespace;
            $property_list = $objectAdminLTE->getModelPropertyList($object);
            $countProperty = count($property_list);

            for ($j=0; $j < $countProperty; $j++) { 
                $property = $property_list[$j];

                $list[$index]['id'] = ($index + 1);
                $list[$index]['model'] = $model;
                $list[$index]['attribute'] = $property;

                $index++;

                $list[$index]['id'] = ($index + 1);
                $list[$index]['model'] = $model;
                $list[$index]['attribute'] = $property . '/display_text';

                $index++;

            } // for ($j=0; $j < $countProperty; $j++) { 
        } // for ($i=0; $i < $countModels; $i++) {

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $columns;
        $objectHTMLDB->printHTMLDBList();

        return;

    }

    public function get_widgetconfig(Request $request)
    {
        $parameters = $request->route()->parameters();

        $pagename = isset($parameters['pagename'])
                ? htmlspecialchars($parameters['pagename'])
                : '';

        $objectAdminLTE = new AdminLTE();

        $Widgets = $objectAdminLTE->getPageLayout($pagename);
        
        $list = array();
        $list[0]['id'] = 1;
        $list[0]['widget_json'] = json_encode($Widgets,
                JSON_HEX_QUOT |
                JSON_HEX_TAG |
                JSON_HEX_AMP |
                JSON_HEX_APOS);

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;

    }

    public function post_widgetconfig(Request $request) {
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
        $parameters = $request->route()->parameters();

        $pagename = isset($parameters['pagename'])
                ? htmlspecialchars($parameters['pagename'])
                : '';
        
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
            $widget_json = html_entity_decode(htmlspecialchars($this->row['widget_json']));

            if ('' == $widget_json) {
                $widget_json = '[]';
            } 
            
            $objectAdminLTE = new AdminLTE();

            $widgets = $objectAdminLTE->base64encode($widget_json);

            $user_data = $objectAdminLTE->getUserData();

            $objectAdminLTEUserLayout = null;
            $objectAdminLTEUserLayouts = AdminLTEUserLayout::where('deleted', false)->where('adminlteuser_id', $user_data['id'])->where('pagename', $pagename)->get();

            if (count($objectAdminLTEUserLayouts) > 0) {
                $objectAdminLTEUserLayout = $objectAdminLTEUserLayouts[0];
            } else {
                $objectAdminLTEUserLayout = new AdminLTEUserLayout();
            }
            
            $objectAdminLTEUserLayout->adminlteuser_id = $user_data['id'];
            $objectAdminLTEUserLayout->pagename = $pagename;
            $objectAdminLTEUserLayout->widgets = $widgets;
            $objectAdminLTEUserLayout->save(); 

            $result['lastMessage'] = 'UPDATED';
            $result['messageCount'] = 1;
        }

        $objectHTMLDB->lastError = $result['lastError'];
        $objectHTMLDB->errorCount = $result['errorCount'];
        $objectHTMLDB->lastMessage = $result['lastMessage'];
        $objectHTMLDB->messageCount = $result['messageCount'];

        $objectHTMLDB->printResponseJSON();
        return;
    }
}
