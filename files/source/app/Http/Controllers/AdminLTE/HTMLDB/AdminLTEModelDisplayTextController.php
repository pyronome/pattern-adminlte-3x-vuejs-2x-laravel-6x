<?php

namespace App\Http\Controllers\AdminLTE\HTMLDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEModelDisplayText;
use App\HTMLDB\HTMLDB;

class AdminLTEModelDisplayTextController extends Controller
{
    public $columns = [
        'id',
        'model',
        'display_text_json'
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

        /* {{@snippet:end_check_values}} */

        return $result;
    }

    
    
    

    function get_model_display_texts(Request $request)
    {
        $list = array();
        
        $exceptions = ['AdminLTE', 'AdminLTELayout', 'AdminLTEModelDisplayText', 'AdminLTEUserLayout', 'AdminLTEVariable', 'HTMLDB', 'User'];

        $objectAdminLTE = new AdminLTE();
        $Models = $objectAdminLTE->getModelList($exceptions);
        $displayTexts = $objectAdminLTE->getAllModelDisplayTexts();

        $countModels = count($Models);
        $index = 0;

        for ($i=0; $i < $countModels; $i++) { 
            $model = $Models[$i];

            $list[$index]['id'] = ($index + 1);
            $list[$index]['model'] = $model;
            $list[$index]['display_text_json'] = json_encode($displayTexts[$model], (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));

            $index++;
        } // for ($i=0; $i < $countModels; $i++) { 

        
        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }
    
    public function get_model_property_list(Request $request) {
        $columns = [
            'id',
            'model',
            'property',
            'type'
        ];

        $list = array();
        
        $exceptions = ['AdminLTE', 'AdminLTELayout', 'AdminLTEModelDisplayText', 'AdminLTEUserLayout', 'AdminLTEVariable', 'HTMLDB', 'User'];

        $objectAdminLTE = new AdminLTE();
        $Models = $objectAdminLTE->getModelList($exceptions);

        $countModels = count($Models);
        $index = 0;

        for ($i=0; $i < $countModels; $i++) { 
            $model = $Models[$i];
            
            $modelNameWithNamespace = ('\\App\\AdminLTE\\' . $model);

            if (!class_exists($modelNameWithNamespace)) {
                $modelNameWithNamespace = ('\\App\\' . $model);
            }
            
            $property_list = $modelNameWithNamespace::$property_list;
            $countProperty = count($property_list);

            for ($j=0; $j < $countProperty; $j++) { 
                $list[$index]['id'] = ($index + 1);
                $list[$index]['model'] = $model;
                $list[$index]['property'] = $property_list[$j]['name'];
                $list[$index]['type'] = $property_list[$j]['type'];

                $index++;
            } // for ($j=0; $j < $countProperty; $j++) { 
        } // for ($i=0; $i < $countModels; $i++) { 

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }
    
    public function post_model_display_texts(Request $request)
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
            $model = htmlspecialchars($this->row['model']);
            $display_text_json = html_entity_decode(htmlspecialchars($this->row['display_text_json']));

            if ('' == $display_text_json) {
                $display_text_json = '[]';
            } 
            
            $objectAdminLTE = new AdminLTE();
            $display_texts = $objectAdminLTE->base64encode($display_text_json);

            $objectAdminLTEModelDisplayText = null;
            $objectAdminLTEModelDisplayTexts = AdminLTEModelDisplayText::where('deleted', false)->where('model', $model)->get();

            if (count($objectAdminLTEModelDisplayTexts) > 0) {
                $objectAdminLTEModelDisplayText = $objectAdminLTEModelDisplayTexts[0];
            } else {
                $objectAdminLTEModelDisplayText = new AdminLTEModelDisplayText();
            }
            
            $objectAdminLTEModelDisplayText->model = $model;
            $objectAdminLTEModelDisplayText->display_texts = $display_texts;
            $objectAdminLTEModelDisplayText->save(); 
        }

        $objectHTMLDB->lastError = $result['lastError'];
        $objectHTMLDB->errorCount = $result['errorCount'];
        $objectHTMLDB->lastMessage = $result['lastMessage'];
        $objectHTMLDB->messageCount = $result['messageCount'];

        $objectHTMLDB->printResponseJSON();
        return;
    }
}
