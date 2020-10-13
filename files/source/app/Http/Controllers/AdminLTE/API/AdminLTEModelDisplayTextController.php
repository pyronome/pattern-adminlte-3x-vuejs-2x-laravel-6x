<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\AdminLTE\AdminLTEModelDisplayText;
use App\Http\Requests\AdminLTE\API\AdminLTEModelDisplayTextPOSTRequest;

class AdminLTEModelDisplayTextController extends Controller
{

    public function get(Request $request)
    {

        $objectAdminLTE = new AdminLTE();
        $list = array();

        $Models = $objectAdminLTE->getModelList();
        $displayTexts = $objectAdminLTE->getAllModelDisplayTexts();
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

        return $list;
    }

    public function get_model_property_list(Request $request)
    {      
        $list = [];        
        $exceptions = ['AdminLTE', 'AdminLTELayout', 'AdminLTEModelDisplayText', 'AdminLTEModelOption', 'AdminLTEUserLayout', 'AdminLTEVariable', 'User'];

        $objectAdminLTE = new AdminLTE();
        $Models = $objectAdminLTE->getModelList($exceptions);

        $countModels = count($Models);
        $index = 0;

        for ($i=0; $i < $countModels; $i++) { 
            $model = $Models[$i];
            
            $modelNameWithNamespace = $objectAdminLTE->getModelNameWithNamespace($model);
            
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

        return $list;
    }

    public function post(AdminLTEModelDisplayTextPOSTRequest $request)
    {
        $objectAdminLTE = new AdminLTE();
        $display_texts = $objectAdminLTE->base64Encode(rawurldecode(htmlspecialchars_decode($request->input('display_text_json'))));
        $model = $request->input('model');

        $objectDisplayText = null;
        $objectDisplayTexts = AdminLTEModelDisplayText::where('deleted', false)->where('model', $model)->get();

        if (count($objectDisplayTexts) > 0) {
            $objectDisplayText = $objectDisplayTexts[0];
        } else {
            $objectDisplayText = new AdminLTEModelDisplayText();
        }

        $objectDisplayText->model = $model;
        $objectDisplayText->display_texts = $display_texts;
        $objectDisplayText->save();

        return ['message' => "Success"];
    }

}
