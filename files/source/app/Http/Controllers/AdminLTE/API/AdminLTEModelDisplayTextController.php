<?php

namespace App\Http\Controllers\AdminLTE\HTMLDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE;
use App\AdminLTEUser;
use App\HTMLDB;

class AdminLTEModelDisplayTextController extends Controller
{

    public $columns = [];
    public $protectedColumns = [];
    public $row = [];

    public function get_model_display_texts(Request $request)
    {
        $this->columns = [
            'id',
            'model',
            'display_text_json'
        ];

        $adminLTE = new AdminLTE();
        $list = array();

        $Models = $adminLTE->getModelList();
        $displayTexts = $adminLTE->getAllModelDisplayTexts();

        $countModels = count($Models);
        $index = 0;

        for ($i=0; $i < $countModels; $i++) { 
            $model = $Models[$i];

            $list[$index]['id'] = ($index + 1);
            $list[$index]['model'] = $model;
            $list[$index]['display_text_json'] = json_encode(
                    $displayTexts[$model],
                    (JSON_HEX_QUOT
                    | JSON_HEX_TAG
                    | JSON_HEX_AMP
                    | JSON_HEX_APOS));

            $index++;
        } // for ($i=0; $i < $countModels; $i++) { 

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }

    public function get_model_property_list(Request $request)
    {
        $this->columns = [
            'id',
            'model',
            'property',
            'type'
        ];

        $adminLTE = new AdminLTE();
        $list = array();

        $Models = $adminLTE->getModelList();

        $countModels = count($Models);
        $index = 0;

        for ($i=0; $i < $countModels; $i++)
        {
            $model = $Models[$i];
            
            $modelNameWithNamespace = ('\\App\\' . $model);
            $object = new $modelNameWithNamespace;
            $property_list = $adminLTE->getModelPropertyList($object);
            $countProperty = count($property_list);

            for ($j=0; $j < $countProperty; $j++)
            {
                $list[$index]['id'] = ($index + 1);
                $list[$index]['model'] = $model;
                $list[$index]['property'] = $property_list[$j];
                $list[$index]['type'] = 'text';

                $index++;
            } // for ($j=0; $j < $countProperty; $j++) { 
        } // for ($i=0; $i < $countModels; $i++) { 

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }

    public function post_model_display_texts(Request $request)
    {

        $objectHTMLDB = new HTMLDB();
        $adminLTE = new AdminLTE();

        $this->row = $objectHTMLDB->requestPOSTRow(
                $request->all(),
                ['model', 'display_text_json'],
                $this->protectedColumns,
                0,
                true);

        if ('' == $this->row['display_text_json'])
        {
            $this->row['display_text_json'] = '[]';
        } // if ('' == $this->row['display_text_json'])

        $display_texts = $adminLTE->base64Encode($display_text_json);
        
        $modelDisplayText = \App\AdminLTEModelDisplayText::where('deleted', false)
                ->where('model', $model)
                ->first();

        if ($modelDisplayText != null) {
            $modelDisplayText->display_texts = $display_texts;
            $modelDisplayText->update();
        } else {
            $modelDisplayText = new \App\AdminLTEModelDisplayText;
            $modelDisplayText->model = $model;
            $modelDisplayText->display_texts = $display_texts;
            $modelDisplayText->insert();
        } // if ($modelDisplayText != null) {

        $result['messageCount'] = 1;
        $result['lastMessage'] = 'UPDATED';

        $objectHTMLDB->errorCount = $result['errorCount'];
        $objectHTMLDB->messageCount = $result['messageCount'];
        $objectHTMLDB->lastError = $result['lastError'];
        $objectHTMLDB->lastMessage = $result['lastMessage'];
        $objectHTMLDB->printResponseJSON();
        return;

    }

}
