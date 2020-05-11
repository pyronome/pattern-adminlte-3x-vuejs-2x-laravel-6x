<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE;
use App\AdminLTEUser;
use App\Http\Requests\AdminLTE\API\AdminLTELayoutPOSTRequest;

class AdminLTELayoutController extends Controller
{

    public function get_attributes(Request $request)
    {

        $list = array();

        $adminLTE = new AdminLTE();

        $Models = $adminLTE->getModelList();

        $countModels = count($Models);
        $index = 0;

        for ($i=0; $i < $countModels; $i++)
        {
            $model = ('\\App\\' . $Models[$i]);
            
            $object = new $model;
            $property_list = $adminLTE->getModelPropertyList($object);
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

        return $list;

    }

    public function get_widgetconfig(Request $request)
    {

        $response = [];

        $parameters = $request->route()->parameters();

        $pageName = isset($parameters['pageName'])
                ? htmlspecialchars($parameters['pageName'])
                : '';

        $adminLTE = new AdminLTE();

        $Widgets = $adminLTE->getPageLayout($pageName);

        $response['widget_json'] = json_encode($Widgets,
                JSON_HEX_QUOT |
                JSON_HEX_TAG |
                JSON_HEX_AMP |
                JSON_HEX_APOS);

        return $response;

    }

    public function post(AdminLTELayoutPOSTRequest $request)
    {

    }

}
