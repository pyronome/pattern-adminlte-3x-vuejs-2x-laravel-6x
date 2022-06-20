<?php

namespace App\Http\Controllers\Wisilo\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;
use App\Wisilo\WisiloModelDisplayText;
use App\Http\Requests\Wisilo\API\WisiloModelDisplayTextPOSTRequest;

class WisiloModelDisplayTextController extends Controller
{

    public function get(Request $request)
    {

        $objectWisilo = new Wisilo();
        $list = array();

        $Models = $objectWisilo->getModelList();
        $displayTexts = $objectWisilo->getAllModelDisplayTexts();
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

        $objectWisilo = new Wisilo();
        $Models = $objectWisilo->getModelList();

        $countModels = count($Models);
        $index = 0;

        for ($i=0; $i < $countModels; $i++) { 
            $model = $Models[$i];
            
            $modelNameWithNamespace = $objectWisilo->getModelNameWithNamespace($model);
            
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

    public function post(WisiloModelDisplayTextPOSTRequest $request)
    {
        $objectWisilo = new Wisilo();
        $display_texts = $objectWisilo->base64Encode(rawurldecode(htmlspecialchars_decode($request->input('display_text_json'))));
        $model = $request->input('model');

        $objectDisplayText = null;
        $objectDisplayTexts = WisiloModelDisplayText::where('deleted', false)->where('model', $model)->get();

        if (count($objectDisplayTexts) > 0) {
            $objectDisplayText = $objectDisplayTexts[0];
        } else {
            $objectDisplayText = new WisiloModelDisplayText();
        }

        $objectDisplayText->model = $model;
        $objectDisplayText->display_texts = $display_texts;
        $objectDisplayText->save();

        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

}
