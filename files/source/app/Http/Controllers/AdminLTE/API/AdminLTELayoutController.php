<?php

namespace App\Http\Controllers\AdminLTE\API;

use Illuminate\Support\Facades\DB;
use PDO;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTELayout;
use App\AdminLTE\AdminLTEUser;
use App\AdminLTE\AdminLTEUserGroup;
use App\AdminLTE\AdminLTEUserLayout;
use App\AdminLTE\AdminLTEWidgetHelper;
use App\Http\Requests\AdminLTE\API\AdminLTELayoutPOSTRequest;


class AdminLTELayoutController extends Controller
{

    public function get_attributes(Request $request)
    {

        $list = array();

        $objectAdminLTE = new AdminLTE();

        $Models = $objectAdminLTE->getModelList();
        $countModels = count($Models);
        $index = 0;

        for ($i=0; $i < $countModels; $i++)
        {
            $model = $Models[$i];
            $property_list = $objectAdminLTE->getModelPropertyList($model);
            $countProperty = count($property_list);

            for ($j=0; $j < $countProperty; $j++) { 
                $property = $property_list[$j]['name'];

                $list[$index]['id'] = ($index + 1);
                $list[$index]['model'] = $model;
                $list[$index]['attribute'] = $property;

                $index++;

                $list[$index]['id'] = ($index + 1);
                $list[$index]['model'] = $model;
                $list[$index]['attribute'] = $property . '__displaytext__';

                $index++;

            } // for ($j=0; $j < $countProperty; $j++) { 
        } // for ($i=0; $i < $countModels; $i++) {

        return [
            'attributes' => $list
        ];

    }

    public function get_widgetconfig(Request $request)
    {

        $response = [];

        $parameters = $request->route()->parameters();

        $pageName = isset($parameters['pageName'])
                ? htmlspecialchars($parameters['pageName'])
                : '';

        $objectAdminLTE = new AdminLTE();

        $Widgets = $objectAdminLTE->getPageWidgetConfig($pageName);

        $widgetJson = json_encode($Widgets,
                JSON_HEX_QUOT |
                JSON_HEX_TAG |
                JSON_HEX_AMP |
                JSON_HEX_APOS);

        return [
            /* 'widgetJson' =>  */$widgetJson
        ];

    }

    public function post_widgetconfig(AdminLTELayoutPOSTRequest $request)
    {
        $pagename = $request->input('pagename');
        $widgetJSON = html_entity_decode(htmlspecialchars($request->input('widgetJSON')));

        if ('' == $widgetJSON) {
            $widgetJSON = '[]';
        } 
        
        $objectAdminLTE = new AdminLTE();

        $widgets = $objectAdminLTE->base64encode($widgetJSON);

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

        return ['message' => "Success"];
    }

    public function get_page_widgets(Request $request)
    {
        $list = [];

        $User = auth()->guard('adminlteuser')->user();

        $parameters = $request->route()->parameters();

        $pagename = isset($parameters['pagename'])
                ? htmlspecialchars($parameters['pagename'])
                : '';


                
        $objectAdminLTE = new AdminLTE();

        $Widgets = $objectAdminLTE->getPageLayout($pageName);
        $countWidgets = count($Widgets);
        $index = 0;

        for ($i=0; $i < $countWidgets; $i++) { 
            $Widget = $Widgets[$i];

            $show_widget = true;

            if ('' != $Widget['model']) {
                $modelNameWithNamespace = $objectAdminLTE->getModelNameWithNamespace($Widget['model']);
                $show_widget = $User->can('viewAny', $modelNameWithNamespace);
            }

            if (!$show_widget) {
                continue;
            }

            $list[$index]['id'] = ($index + 1);
            $list[$index]['type'] = $Widget['type'];
            $list[$index]['model'] = $Widget['model'];
            $list[$index]['text'] = $Widget['text'];
            $list[$index]['href'] = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.mainfolder') . '/' . $Widget['href'];

            $sizeCSV = $Widget['size'];
            $sizes = explode(',', $sizeCSV);

            $list[$index]['size'] = 'col-lg-' . $sizes[0] . ' col-md-' . $sizes[1] . ' col-xs-' . $sizes[2];
            $list[$index]['visibility'] = $Widget['visibility'];
            $list[$index]['order'] = $Widget['order'];
            $list[$index]['icon'] = $Widget['icon'];
            $list[$index]['iconbackground'] = $Widget['iconbackground'];
            $list[$index]['limit'] = $Widget['limit'];
            $list[$index]['onlylastrecord'] = $Widget['onlylastrecord'];
            $list[$index]['columns'] = $Widget['columns'];
            $list[$index]['values'] = $Widget['values'];

            $index++;
        } // for ($i=0; $i < $countWidgets; $i++) {

        return $list;
    }

    public function get_widgets(Request $request)
    {
        $parameters = $request->route()->parameters();

        $pagename = isset($parameters['pagename'])
                ? htmlspecialchars($parameters['pagename'])
                : '';

        $objectAdminLTE = new AdminLTE();

        // widgets
        $widgets = $objectAdminLTE->getUserWidgets($pagename);
        
        return [
            'page_widgets' => $widgets
        ];

    }

    public function get_active_widgets(Request $request)
    {
        $parameters = $request->route()->parameters();

        $pagename = isset($parameters['pagename'])
                ? htmlspecialchars($parameters['pagename'])
                : '';

        $objectAdminLTE = new AdminLTE();

        // widgets
        $active_widgets = $objectAdminLTE->getUserActiveWidgets($pagename);
        
        return [
            'active_widgets' => $active_widgets
        ];

    }

    public function post_layout(Request $request) {
        $objectAdminLTE = new AdminLTE();
        $currentUser = auth()->guard('adminlteuser')->user();
        $currentGroupId = $currentUser->adminlteusergroup_id;
        $pagename = $request->input('pagename');
        $layoutdata = $request->input('layoutdata');

        AdminLTELayout::query()->where('adminlteusergroup_id', $currentGroupId)
            ->where('pagename', $pagename)
            ->delete();

        foreach ($layoutdata as $__order => $data) {
            $general_data = $data['general'];
            $content_data = $data['content'];
            $data_source = $data['data_source'];

            $object = new AdminLTELayout();
            $object->enabled = $general_data['enabled'];
            $object->__order = $__order;
            $object->adminlteusergroup_id = $currentGroupId;
            $object->pagename = $pagename;
            $object->widget = $general_data['widget'];
            $object->title = $general_data['title'];
            $object->grid_size = $general_data['grid_size'];
            $object->icon = $general_data['icon'];
            $object->meta_data_json = json_encode($content_data);
            $object->data_source_json = isset($data_source) ? json_encode($data_source) : '{}';
            $object->conditional_data_json = isset($general_data['conditional_data_json']) 
                ? $general_data['conditional_data_json']
                : '[]';
            $object->save();

            $conditional_data = json_decode($object->conditional_data_json);

            if (json_last_error() === JSON_ERROR_NONE) {
                $objectAdminLTE->saveLayoutConditionalData($object->id, $conditional_data);
            }
        }
    }

    public function get_filter_options(Request $request) {    
        $data = [];

        $parameters = $request->route()->parameters();

        $pagename = isset($parameters['pagename'])
                ? htmlspecialchars($parameters['pagename'])
                : '';

        // Widget Pages
        $objectList = AdminLTELayout::where('deleted', 0)->groupBy('pagename')->orderBy('pagename', 'asc')->get();

        $list = [];
        $index = 0;

        $list[$index]['id'] = '__global';
        $list[$index]['text'] = 'Global Widgets';
        $index++;

        foreach ($objectList as $object) {
            if ($pagename != $object->pagename) {
                $list[$index]['id'] = $object->pagename;
                $list[$index]['text'] = $object->pagename . ' | ' . 'Widgets';
                $index++;
            }
        } // foreach ($objectList as $object)

        $data['widget_page'] = $list;

        return [
            'options' => $data
        ];
    }

    public function get_model_list(Request $request) {    
        $list = [];
        $index = 0;

        $list[$index]['id'] = '';
        $list[$index]['text'] = __('Please Select');
        $index++;

        $list[$index]['id'] = 'AdminLTEUser';
        $list[$index]['text'] = 'AdminLTEUser';
        $index++;

        $list[$index]['id'] = 'AdminLTEUserGroup';
        $list[$index]['text'] = 'AdminLTEUserGroup';
        $index++;

        $objectAdminLTE = new AdminLTE();
		$Models = $objectAdminLTE->getModelList();
		$countModels = count($Models);

		for ($i=0; $i < $countModels; $i++) {
            $list[$index]['id'] = $Models[$i];
            $list[$index]['text'] = $Models[$i];
            $index++;
        }

        return [
            'list' => $list
        ];
    }

    public function get_model_properties(Request $request)
    {
        $parameters = $request->route()->parameters();

        $model = isset($parameters['model'])
                ? htmlspecialchars($parameters['model'])
                : '';

        $list = array();
        $index = 0;

        $objectAdminLTE = new AdminLTE();
        $property_list = $objectAdminLTE->getModelPropertyList($model);
        $countProperty = count($property_list);

        for ($j=0; $j < $countProperty; $j++) { 
            $property = 

            $list[$index]['id'] = $property_list[$j]['name'];
            $list[$index]['text'] = $property_list[$j]['title'];

            $index++;
        } // for ($j=0; $j < $countProperty; $j++) { 

        return [
            'list' => $list
        ];

    }

    public function get_infoboxvalue_by_simple_calculation($data_source) {
        $calculationResult = [];

        $function = $data_source['function'];
        $model = $data_source['model'];
        $property = $data_source['property'];
        $condition = 1;

        $tablename = strtolower($model) . "table";

        $selectSQL = 'SELECT ' . $function . '(' . $property . ') as result '
            . ' FROM ' . $tablename
            . ' WHERE ' . $condition;
        
        try {
            $connection = DB::connection()->getPdo();
        } catch (PDOException $e) {
            print($e->getMessage());
        }
                
        $objPDO = $connection->prepare($selectSQL);
        $objPDO->execute();
        $data = $objPDO->fetchAll();

        if (isset($data)) {
            if (isset($data[0])) {
                $calculationResult = $data[0];//["result"];
            }
        }

        return $calculationResult;
    }

    public function get_infoboxvalue_by_advanced_calculation($query) {
        $calculationResult = [];
        $condition = 1;

        try {
            $connection = DB::connection()->getPdo();
        } catch (PDOException $e) {
            print($e->getMessage());
        }
                
        $objPDO = $connection->prepare($query);
        $objPDO->execute();
        $data = $objPDO->fetchAll();

        if (isset($data)) {
            if (isset($data[0])) {
                $calculationResult = $data[0];//["result"];
            }
        }

        return $calculationResult;
    }

    public function get_infoboxvalue_by_extreme_calculation($data_source) {
        $calculationResult = [];
        $condition = 1;

        try {
            $connection = DB::connection()->getPdo();
        } catch (PDOException $e) {
            print($e->getMessage());
        }
                
        //$objPDO = $connection->prepare($query);
        $objPDO = $connection->prepare("select * from countrytable where deleted=0;");
        $objPDO->execute();
        $data = $objPDO->fetchAll();

        echo '<div style="display:block">';
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        echo '</div>';
        die();

        if (isset($data)) {
            if (isset($data[0])) {
                $calculationResult = $data[0];//["result"];
            }
        }

        return $calculationResult;
    }

    public function get_infobox_data(Request $request)
    {   
        $returnData = null;

        $parameters = $request->route()->parameters();

        $widgetParametersEncoded = isset($parameters['parameters'])
                ? htmlspecialchars($parameters['parameters'])
                : '';

        $objectAdminLTEWidgetHelper = new AdminLTEWidgetHelper();
        $widgetParameters = json_decode(base64_decode($widgetParametersEncoded), (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));

        if (!isset($widgetParameters['layout_id'])) {
            return $returnData;
        }

        $layoutId = $widgetParameters['layout_id'];
        $url_parameters = $widgetParameters['url_parameters'];
        $request_parameters = $widgetParameters['request_parameters'];

        $objectLayout = AdminLTELayout::where('id', $layoutId)->first();

        if (null !== $objectLayout) {
            $metaData = json_decode($objectLayout->meta_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));
            $data_source = json_decode($objectLayout->data_source_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));

            if ('simple' == $data_source['calculation_type']) {
                $queryResult = $this->get_infoboxvalue_by_simple_calculation($data_source);
            } else if ('advanced' == $data_source['calculation_type']) {
                $query = $this->getParsedValue($data_source['query'], '', $url_parameters, $request_parameters);
                $queryResult = $this->get_infoboxvalue_by_advanced_calculation($query);
            } else if ('extreme' == $data_source['calculation_type']) {
                $queryResult = $this->get_infoboxvalue_by_extreme_calculation($data_source);
            } 

            foreach ($metaData as $key => $value) {
                $returnData[$key] = $this->getParsedValue($value, $queryResult, $url_parameters, $request_parameters);
            }

            $conditionalData = json_decode($objectLayout->conditional_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));
            foreach ($conditionalData as $key => $conditionData) {
                $function_name = $conditionData['guid'];

                require_once(storage_path('app/widget/condition/'. $function_name . '.php'));
                    
                $conditionResultData = $function_name($objectAdminLTEWidgetHelper, $queryResult, $url_parameters, $request_parameters);

                if ($conditionResultData['success']) {
                    $conditionalFields = $conditionResultData['data'];
                    foreach ($conditionalFields as $index => $fieldData) {
                        $returnData[$fieldData->id] = $this->getParsedValue($fieldData->value, $queryResult, $url_parameters, $request_parameters);
                    }
                }
            }

            $returnData['result'] = isset($queryResult['result']) 
                ? $queryResult['result']
                : 0;
        }

        return $returnData;
    }

    public function getParsedValue($display_text, $queryResult, $url_parameters, $request_parameters)
    {
        $currentUser = auth()->guard('adminlteuser')->user();
        $objectAdminLTEWidgetHelper = new AdminLTEWidgetHelper();
        $objectAdminLTE = new AdminLTE();
        $parsed = $objectAdminLTE->getStringBetween($display_text, '{{', '}}');

		while (strlen($parsed) > 0) {
            $parsedWithMustache = '{{' . $parsed . '}}';
			$partResult = '';

			$textPart = explode('/', $parsed);

            if ('CustomVariables' == $textPart[0]) {
                $__key = $textPart[1];
                $customVariableValue = $objectAdminLTEWidgetHelper->getCustomVariableValue(
                        $currentUser->adminlteusergroup_id, 
                        $__key, 
                        $queryResult, 
                        $url_parameters, 
                        $request_parameters
                );
                
				$partResult = ('' != $customVariableValue)
                    ? $customVariableValue
                    : $__key;

            } else if ('GlobalParameters' == $textPart[0]) {
                $__key = $textPart[1];
                $partResult = $objectAdminLTE->getConfigParameterValue($__key);
            } else if ('UserParameters' == $textPart[0]) {
                $__key = $textPart[1];
                $partResult = $objectAdminLTE->getUserConfigParameterValue($__key, 'user', $currentUser->id);
            } else if ('URLParameters' == $textPart[0]) {
                $__key = $textPart[1];
                $partResult = isset($url_parameters[$__key]) 
                    ? $url_parameters[$__key]
                    : $__key;
            } else if ('RequestParameters' == $textPart[0]) {
                $__key = $textPart[1];
                $partResult = isset($request_parameters[$__key]) 
                    ? $request_parameters[$__key]
                    : $__key;
            } else if ('QueryResultFields' == $textPart[0]) {
                $__key = $textPart[1];
                $partResult = isset($queryResult[$__key]) 
                    ? $queryResult[$__key] 
                    : $__key;
            } else {
                $__key = $textPart[1];
                $partResult = $__key;
            }

            
            $display_text = str_replace($parsedWithMustache, $partResult, $display_text);
			$temp_text = $display_text;
			$parsed = $objectAdminLTE->getStringBetween($temp_text, '{{', '}}');
		} // while (strlen($parsed) > 0) {

        return htmlspecialchars_decode($display_text);
    }
    
    public function __EX__get_infoboxvalue(Request $request)
    {   
        $parameters = $request->route()->parameters();

        $model = isset($parameters['model'])
                ? htmlspecialchars($parameters['model'])
                : '';

        $objectAdminLTE = new AdminLTE();
        $modelNameWithNamespace = $objectAdminLTE->getModelNameWithNamespace($model);

        return [
            'count' => $modelNameWithNamespace::where('deleted', false)->count()
        ];
    }

    

    public function get_recordgraph(Request $request)
    {
        $parameters = $request->route()->parameters();

        $pageName = isset($parameters['pageName'])
                ? htmlspecialchars($parameters['pageName'])
                : '';
        $model = isset($parameters['model'])
                ? htmlspecialchars($parameters['model'])
                : '';

        $objectAdminLTE = new AdminLTE();

        $dateFormat = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.dateformat');
        $yearMonthFormat = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.yearmonthformat');
        
        $modelNameWithNamespace = $objectAdminLTE->getModelNameWithNamespace($model);

        $Widgets = $objectAdminLTE->getPageLayout($pageName);
        $graphProperties = $objectAdminLTE->getRecordGraphProperties($Widgets, $model);
        
        $text = $graphProperties['text'];
        $sizes = explode(',', $graphProperties['sizeCSV']);
        $size = 'col-lg-' . $sizes[0] . ' col-md-' . $sizes[1] . ' col-xs-' . $sizes[2];

        $graphType = $graphProperties['type'];

        $period = (0 - $graphProperties['period']);
        $fromDate = strtotime($period . ' month');

        $graphData = array();

        $objectList = $modelNameWithNamespace::where('deleted', false)
                ->whereDate('created_at', '>=', date('Y-m-d H:i:s', $fromDate))
                ->orderBy('created_at', 'asc')
                ->get();

        $object = NULL;
        $index = 0;
            
        if ('daily' == $graphType) {
            foreach ($objectList as $object)
            {
                $created_at = $object->created_at->format($dateFormat);

                if (!isset($graphData[$created_at])) {
                    $graphData[$created_at] = 0;
                }

                $graphData[$created_at]++;
            } // foreach ($objectList as $object)
        } else if ('monthly' == $graphType) {
            foreach ($objectList as $object)
            {
                $created_at = $object->created_at->format($yearMonthFormat);

                if (!isset($graphData[$created_at])) {
                    $graphData[$created_at] = 0;
                }

                $graphData[$created_at]++;
            } // foreach ($objectList as $object)
        } // if ('daily' == $graphType) {
        
        $keys = array_keys($graphData);
        $countKeys = count($keys);
        
        $graphJSON = '';
        for ($i=0; $i < $countKeys; $i++) {
            $created_at = $keys[$i];
            $countRecord = $graphData[$created_at];

            if ($graphJSON != '') {
                $graphJSON .= ',';
            } // if ($graphJSON != '') {

            $graphJSON .= '{';
            $graphJSON .= ('"date":"' . $created_at . '",');
            $graphJSON .= ('"record":' . $countRecord . '');
            $graphJSON .= '}';
        }
        $graphJSON = ('[' . $graphJSON . ']');

        return [
            'id' => 1,
            'text' => $text,
            'size' => $size,
            'graphJSON' => rawurlencode($graphJSON)
        ];
    }

    public function get_recordlist_by_advanced_calculation($query) {
        $calculationResult = [];
        $condition = 1;

        try {
            $connection = DB::connection()->getPdo();
        } catch (PDOException $e) {
            print($e->getMessage());
        }
                
        $objPDO = $connection->prepare($query);
        $objPDO->execute();
        $data = $objPDO->fetchAll();

        /* echo '<div style="display:block">';
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        echo '</div>';
        die(); */

        if (isset($data)) {
            $calculationResult = $data;//["result"];
        }

        return $calculationResult;
    }

    public function get_recordlist_by_extreme_calculation($query) {
        $calculationResult = [];
        $condition = 1;

        try {
            $connection = DB::connection()->getPdo();
        } catch (PDOException $e) {
            print($e->getMessage());
        }
                
        //$objPDO = $connection->prepare($query);
        $objPDO = $connection->prepare("select * from countrytable where deleted=0;");
        $objPDO->execute();
        $data = $objPDO->fetchAll();

        /* echo '<div style="display:block">';
        echo '<pre>';
        print_r($data);
        echo '</pre>';
        echo '</div>';
        die(); */

        if (isset($data)) {
            $calculationResult = $data;//["result"];
        }

        return $calculationResult;
    }

    public function get_recordlist_data(Request $request)
    {
        $returnData = null;

        $parameters = $request->route()->parameters();

        $widgetParametersEncoded = isset($parameters['parameters'])
                ? htmlspecialchars($parameters['parameters'])
                : '';

        $objectAdminLTEWidgetHelper = new AdminLTEWidgetHelper();
        $widgetParameters = json_decode(base64_decode($widgetParametersEncoded), (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));

        if (!isset($widgetParameters['layout_id'])) {
            return $returnData;
        }

        $layoutId = $widgetParameters['layout_id'];
        $url_parameters = $widgetParameters['url_parameters'];
        $request_parameters = $widgetParameters['request_parameters'];

        $objectLayout = AdminLTELayout::where('id', $layoutId)->first();

        $record_list_title = '';
        $column_titles = [];
        $column_variables = [];
        $visible_columns = [];
        $list = [];

        if (null !== $objectLayout) {
            $metaData = json_decode($objectLayout->meta_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));
            $data_source = json_decode($objectLayout->data_source_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));

            if ('simple' == $data_source['calculation_type']) {
                $queryResult = $this->get_infoboxvalue_by_simple_calculation($data_source);
            } else if ('advanced' == $data_source['calculation_type']) {
                $query = $this->getParsedValue($data_source['query'], '', $url_parameters, $request_parameters);
                $queryResult = $this->get_recordlist_by_advanced_calculation($query);
            } else if ('extreme' == $data_source['calculation_type']) {
                $queryResult = $this->get_recordlist_by_extreme_calculation($data_source);
            }

            $record_list_title = $this->getParsedValue($metaData['record_list_title'], $queryResult, $url_parameters, $request_parameters);

            $columns = $metaData['columns'];
            $index = 0;
            foreach ($columns as $column) {
                $objectColumn = (object) $column;

                if ('on' == $objectColumn->visible) {
                    $column_name = $objectColumn->name;

                    $visible_columns[$column_name] = $objectColumn;
                    $column_titles[$index] = $objectColumn->title;
                    $column_variables[$index] = $column_name;

                    $index++;
                }
            }

            $conditionalData = json_decode($objectLayout->conditional_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));

            foreach ($queryResult as $row) {
                $list[$index] = [];
                $list[$index]['id'] = $row['id'];
                $list[$index]['displaytexts'] = [];
                $list[$index]['styles'] = [];
    
                foreach ($column_titles as $title_index => $column_title) {
                    $column_titles[$title_index] = $this->getParsedValue($column_title, $row, $url_parameters, $request_parameters);
                }

                foreach ($column_variables as $column_name) {
                    $objectColumn = $visible_columns[$column_name];
                    $list[$index]['displaytexts'][] = $this->getParsedValue($objectColumn->value, $row, $url_parameters, $request_parameters);
                    $list[$index]['styles'][] = $objectColumn->style;
                }

                foreach ($conditionalData as $conditionData) {
                    $function_name = $conditionData['guid'];
    
                    require_once(storage_path('app/widget/condition/'. $function_name . '.php'));
                        
                    $conditionResultData = $function_name($objectAdminLTEWidgetHelper, $row, $url_parameters, $request_parameters);
    
                    if ($conditionResultData['success']) {
                        $conditionalFields = $conditionResultData['data'];
    
                        foreach ($conditionalFields as $fieldData) {
                            // $returnData[$fieldData->id] = $this->getParsedValue($fieldData->value, $queryResult, $url_parameters, $request_parameters);
                            if ('record_list_title' == $fieldData->id) {
                                $record_list_title = $this->getParsedValue($fieldData->value, $row, $url_parameters, $request_parameters);
                            } else if ('array' == $fieldData->type) {
                                $list[$index]['displaytexts'] = [];
                                $list[$index]['styles'] = [];

                                $columns = $fieldData->items;
    
                                foreach ($columns as $column) {
                                    $column_name = $column->name;
                                    if (isset($visible_columns[$column_name])) {
                                        $list[$index]['displaytexts'][] = $this->getParsedValue($column->value, $row, $url_parameters, $request_parameters);
                                        $list[$index]['styles'][] = $column->style;
                                    }
                                }
                            }
                        }
                    }
                }
    
                $index++;
            } // foreach ($objectList as $object)
        }

        return [
            'record_list_title' => $record_list_title,
            'table_header' => [
                'titles' => $column_titles,
                'variables' => $column_variables
            ],
            'list' => $list
        ];
    }

    public function get_recordlist(Request $request)
    {
        $User = auth()->guard('adminlteuser')->user();

        $parameters = $request->route()->parameters();

        $pageName = isset($parameters['pageName'])
                ? htmlspecialchars($parameters['pageName'])
                : '';
        $model = isset($parameters['model'])
                ? htmlspecialchars($parameters['model'])
                : '';

        $objectAdminLTE = new AdminLTE();

        $dateFormat = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.dateformat');
        $timeFormat = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.timeformat');
        $modelNameWithNamespace = $objectAdminLTE->getModelNameWithNamespace($model);
        
        $search_text = '';
        if ($s = \Request::get('s')) {
            $search_text = $s;
        }

        $page = 1;
        if ($p = \Request::get('p')) {
            $page = $p;
        }

        $sort_variable = 'id';
        if ($v = \Request::get('v')) {
            $sort_variable = $v;
        }

        $sort_direction = 'desc';
        if ($d = \Request::get('d')) {
            $sort_direction = $d;
        }

        $currentWidget = null;
        $objectAdminLTE = new AdminLTE();
        $Widgets = $objectAdminLTE->getPageLayout($pageName);
        
        foreach ($Widgets as $Widget) {
            if (('recordlist' == $Widget['type']) && ($model == $Widget['model'])) {
                $currentWidget = $Widget;
                break;
            }
        }

        $widget_title = $model . ' List';
        $sizeCSV = '12,12,12';
        $variables = ['id', 'created_at__displaytext__', 'updated_at__displaytext__'];
        $widget_table_header = [
            'titles' => ['Id', 'Created At', 'Updated At'],
            'variables' => $variables
        ];
        $limit = 10;
        $onlylastrecord = true;

        if ($currentWidget) {
            $widget_title = $Widget['text'];
            $sizeCSV = $Widget['size'];
            
            if ('' != $Widget['columns']) {
                $titles = explode(',', $Widget['columns']);
                $widget_table_header['titles'] = $titles;
            }

            if ('' != $Widget['values']) {
                $variables = explode(',', $Widget['values']);
                $widget_table_header['variables'] = $variables;
            }

            $limit = $Widget['limit'];
            $onlylastrecord = (1 == intval($Widget['onlylastrecord']));
        }

        $show_pagination = true;
        if ($onlylastrecord) {
            $page = 1;
            $show_pagination = false;
        }
        
        $sizes = explode(',', $sizeCSV);
        $size = 'col-lg-' . $sizes[0] . ' col-md-' . $sizes[1] . ' col-xs-' . $sizes[2];
        
        $widget_options = [
            'title' => $widget_title,
            'size' => $size,
            'table_header' => $widget_table_header,
        ];
        
        $list = [];
        $current_page = 0;
        $last_page = 0;
        $per_page = 0;
        $from = 0;
        $to = 0;
        $total = 0;
        $next_page_url = null;
        $prev_page_url = null;

        $objectList = $modelNameWithNamespace::defaultQuery($search_text, $sort_variable, $sort_direction)->paginate($limit, ['*'], 'page', $page);

        $current_page = $objectList->currentPage();
        $last_page = $objectList->lastPage();
        $per_page = $objectList->perPage();
        $from = (($current_page - 1) * $per_page) + 1;
        $to = ($current_page * $per_page);
        $total = $objectList->total();
        $next_page_url = ($last_page == $current_page) ? null : 'get_recordlist?p=' . ($current_page + 1);
        $prev_page_url = (1 == $current_page) ? null : 'get_recordlist?p=' . ($current_page - 1);
                    
        $index = 0;

        foreach ($objectList as $object) {
            $user_can_view = $User->can('view', $object);

            if ($user_can_view) {
                $displayTexts = $objectAdminLTE->getObjectDisplayTexts($model, $object);
                
                $list[$index] = array();
                $list[$index]['id'] = $object->id;
                $list[$index]['user_can_create'] = $User->can('create', $object);
                $list[$index]['user_can_read'] = $user_can_view;
                $list[$index]['user_can_update'] = $User->can('update', $object);
                $list[$index]['user_can_delete'] = $User->can('delete', $object);
                $list[$index]['user_can_view'] = $User->can('viewAny', $object);
                $list[$index]['displaytexts'] = array();

                foreach ($widget_table_header['variables'] as $variable) {
                    $displayVariable = str_replace('__displaytext__', '', $variable);
                    if (isset($displayTexts[$displayVariable])) {
                        $list[$index]['displaytexts'][] = $displayTexts[$displayVariable];
                    } else if (property_exists($object, $variable)) {
                        $list[$index]['displaytexts'][] = $object->$variable;
                    } else {
                        $list[$index]['displaytexts'][] = '-';
                    }
                }
            }

            $index++;
        } // foreach ($objectList as $object)

        $data = [
            'widget_options' => $widget_options,
            'list' => $list
        ];

        return [
            'search_text' => $search_text,
            'sort_variable' => $sort_variable,
            'sort_direction' => $sort_direction,
            'current_page' => $current_page,
            'last_page' => $last_page,
            'per_page' => $per_page,
            'from' => $from,
            'to' => $to,
            'total' => $total,
            'next_page_url' => $next_page_url,
            'prev_page_url' => $prev_page_url,
            'show_pagination' => $show_pagination,
            'data' => $data
        ];
    }

    public function get_layout_page_options(Request $request) {
        $list = [];
        $index = 0;

        $list[$index]['id'] = 'home';
        $list[$index]['text'] = 'Home';
        $index++;

        $objectAdminLTE = new AdminLTE();
		$Models = $objectAdminLTE->getModelList();
		$countModels = count($Models);

		// get default display texts
		for ($i=0; $i < $countModels; $i++) {
            $model = $Models[$i];

            $list[$index]['id'] = strtolower($model);
            $list[$index]['text'] = $model;
            $index++;
        }

        return [
            'list' => $list
        ];
    }

    public function get_page_layout(Request $request)
    {
        $layout_value = '';

        $User = auth()->guard('adminlteuser')->user();

        $parameters = $request->route()->parameters();

        $pagename = isset($parameters['pagename'])
                ? htmlspecialchars($parameters['pagename'])
                : '';

        $usergroup_id = $User->adminlteusergroup_id;

        $objectAdminLTE = new AdminLTE();
        $objectAdminLTEMetas = $objectAdminLTE->getMetaData('__usergroup_layout', $usergroup_id);
        
        if (count($objectAdminLTEMetas) > 0) {
            $objectAdminLTEMeta = $objectAdminLTEMetas[0];
            
            $metaData = json_decode(
                $objectAdminLTE->base64Decode($objectAdminLTEMeta->meta_value),
                (JSON_HEX_QUOT
                | JSON_HEX_TAG
                | JSON_HEX_AMP
                | JSON_HEX_APOS));

            $iframeData = isset($metaData['iframes']) ? $metaData['iframes'] : [];

            $selected_pages = isset($iframeData['selected_pages']) ? $iframeData['selected_pages'] : [];
            $values = isset($iframeData['values']) ? $iframeData['values'] : [];
            
            if (in_array($pagename,  $selected_pages)) {
                foreach ($values as $value) {
                    if ($pagename == $value['id']) {
                        $layout_value = $value['value'];
                        break;
                    }
                }
            }
        }

        return $layout_value;
    }
}
