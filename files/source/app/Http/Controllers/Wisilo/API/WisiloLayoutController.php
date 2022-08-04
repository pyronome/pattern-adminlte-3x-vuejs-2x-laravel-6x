<?php

namespace App\Http\Controllers\Wisilo\API;

use Illuminate\Support\Facades\DB;
use PDO;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloLayout;
use App\Wisilo\WisiloUser;
use App\Wisilo\WisiloUserGroup;
use App\Wisilo\WisiloUserLayout;
use App\Wisilo\WisiloCustomVariable;
use App\Wisilo\WisiloCustomVariableValue;
use App\Wisilo\WisiloWidgetHelper;
use App\Http\Requests\Wisilo\API\WisiloLayoutPOSTRequest;


class WisiloLayoutController extends Controller
{
    public function get_widgets(Request $request)
    {
        $parameters = $request->route()->parameters();

        $pagename = isset($parameters['pagename'])
                ? htmlspecialchars($parameters['pagename'])
                : '';

        $objectWisilo = new Wisilo();

        // widgets
        $widgets = $objectWisilo->getUserWidgets($pagename);
        
        return [
            'page_widgets' => $widgets
        ];

    }

    public function post_layout(Request $request) {
        $objectWisilo = new Wisilo();
        $currentUser = auth()->guard('wisilouser')->user();
        $currentGroupId = $currentUser->wisilousergroup_id;
        $pagename = $request->input('pagename');
        $layoutdata = $request->input('layoutdata');

        /* echo '<div style="display:block">';
        echo '<pre>';
        print_r($layoutdata);
        echo '</pre>';
        echo '</div>';
        die(); */

        $this->deletePageWidgets($pagename);

        $instanceIdHistory = [];

        foreach ($layoutdata as $__order => $data) {
            $instance_id = $data['instance_id'];
            $parent_instance_id = $data['parent_instance_id'];
            $container_index = isset($data['container_index']) ? $data['container_index'] : '';
            $container_title = isset($data['container_title']) ? $data['container_title'] : '';

            if (isset($instanceIdHistory[$parent_instance_id])) {
                // has parent
                $pagename = $instanceIdHistory[$parent_instance_id] . '-' . $container_index;
            }
            
            $general_data = $data['general'];
            $content_data = $data['content'];
            $data_source = isset($data['data_source']) ? $data['data_source'] : '';

            $object = new WisiloLayout();
            $object->enabled = $general_data['enabled'];
            $object->__order = $__order;
            $object->wisilousergroup_id = $currentGroupId;
            $object->pagename = $pagename;
            $object->container_index = $container_index;
            $object->container_title = $container_title;
            $object->widget = $general_data['widget'];
            $object->title = $general_data['title'];
            $object->grid_size = $general_data['grid_size'];
            $object->icon = $general_data['icon'];
            $object->meta_data_json = json_encode($content_data);
            $object->data_source_json = $this->getFormattedDataSource($data_source);
            $object->variable_mapping_json = isset($data['variable_mapping']) 
                ? json_encode($data['variable_mapping'])
                : '[]';
            $object->conditional_data_json = isset($general_data['conditional_data_json']) 
                ? $general_data['conditional_data_json']
                : '[]';
            $object->save();

            $instanceIdHistory[$instance_id] = $object->id;

            $conditional_data = json_decode($object->conditional_data_json);

            if (json_last_error() === JSON_ERROR_NONE) {
                $objectWisilo->saveLayoutConditionalData($object->id, $conditional_data);
            }
        }
    }

    public function deletePageWidgets($pagename) {
        $objectWisilo = new Wisilo();
        $widgets = $objectWisilo->getUserWidgets($pagename);

        foreach($widgets as $widget) {
            // delete subwidgets
            $this->deletePageWidgetsById($widget->id);

            $widget->delete();
        }

        return;
    }

    public function deletePageWidgetsById($layoutId) {
        $widgets = WisiloLayout::where('deleted', false)
            ->where('pagename', 'like', ($layoutId . '-%'))
            ->get();

        foreach($widgets as $widget) {
            // delete subwidgets
            $this->deletePageWidgetsById($widget->id);

            $widget->delete();
        }

        return;
    }

    public function getFormattedDataSource($data_source) {
        if ('' == $data_source) {
            return '{}';
        }

        if (!isset($data_source['calculation_type'])) {
            return '{}';
        }

        if ('simple' == $data_source['calculation_type']) {
            $meta_data = $data_source['meta_data'];
            $meta_data['query'] = $this->getQueryFromExtremeDataSource($meta_data);
            $data_source['meta_data'] = $meta_data;
        }
        
        return json_encode($data_source);
    }

    public function getQueryFromExtremeDataSource($meta_data) {
        $objectWisilo = new Wisilo();

        $model = $meta_data['model'];
        $basemodel_table_name = strtolower($model) . 'table';
        $basemodel_alias_name = strtolower($model) . '__table__';
        $join_alias_names = [];

        $fields_SQL = $basemodel_alias_name . '.id';
        $from_SQL = 'FROM (' . $basemodel_table_name . ' as ' . $basemodel_alias_name;
        $join_SQL = '';
        $where_SQL = '';
        $order_SQL = '';
        $pagination_SQL = '';

        $searchable_fields = [];
        $field_aliases = [];

        foreach ($meta_data['fields'] as $field) {
            $field_alias = 'customvariable' . $field['customvariable'];
            $field_aliases[$field['guid']] = $field_alias;

            if (isset($field['searchable']) && $field['searchable']) {
                $searchable_fields[] = $field_alias;
            }

            $propertyParts = explode('/', $field['property']);
            $this->setFieldsAndJoinSQL($fields_SQL, $join_SQL, $join_alias_names, $basemodel_alias_name, $basemodel_alias_name, $field['function'], $propertyParts, $field_alias);
        }
        
        $this->setWhereSQL($where_SQL, $searchable_fields, $meta_data['searchtext'], $meta_data['conditions']);
        
        $this->setOrderSQL($order_SQL, $field_aliases, $meta_data['order_fields']);

        $this->setPaginationSQL($pagination_SQL, $meta_data['pagination']);

        $query = 'SELECT ' . $fields_SQL . ' ' . $from_SQL . ' ' . $join_SQL . ') ' . $where_SQL . ' ' . $order_SQL . ' ' . $pagination_SQL;
        
        return $query;
    }

    public function setFieldsAndJoinSQL(&$fields_SQL, &$join_SQL, &$join_alias_names, $first_alias_table, $final_alias_table, $function, $propertyParts, $field_alias) {
        $objectWisilo = new Wisilo();
        // $field['property'] ----> City/title
        // $field['property'] ----> City/country_id/Country/population

        $current_model = array_shift($propertyParts);
        $current_field = array_shift($propertyParts);

        $current_field_multiple_selection = $this->is_field_multiple_selection($current_model, $current_field);
        $current_field_file = $this->is_field_file($current_model, $current_field);

        if ($current_field_file) {
            $relation_table_name = strtolower($current_model) . '__filetable';
            $final_alias_table = $relation_table_name . '_alias';

            $fields_SQL = $fields_SQL . ', ';
            $fields_SQL = $fields_SQL . $function . '(' . $final_alias_table . '.path) as ' . $field_alias;

            if (!isset($join_alias_names[$final_alias_table])) {
                $join_alias_names[$final_alias_table] = 1;

                $join_SQL = $join_SQL
                    . ' LEFT JOIN ' . $relation_table_name . ' as ' . $final_alias_table
                    . ' on ' . $first_alias_table . '.id' . '=' . $final_alias_table . '.object_id';
            }
        } else {
            if (empty($propertyParts)) {
                $fields_SQL = $fields_SQL . ', ';
                $fields_SQL = $fields_SQL . $function . '(' . $final_alias_table . '.' . $current_field . ') as ' . $field_alias;
            } else {
                $next_model = $propertyParts[0];

                if ($current_field_multiple_selection) {
                    // multiple selection
                    $relation_table_name = strtolower($current_model) . '_' . strtolower($current_field);
                    $final_alias_table = $relation_table_name . '_' . strtolower($next_model) . 's';

                    if (!isset($join_alias_names[$final_alias_table])) {
                        $join_alias_names[$final_alias_table] = 1;
        
                        $join_SQL = $join_SQL
                            . ' LEFT JOIN ' . $relation_table_name . ' as ' . $final_alias_table
                            . ' on ' . $first_alias_table . '.id' . '=' . $final_alias_table . '.' . (strtolower($current_model).'_id');
                    }

                    $first_alias_table = $final_alias_table;
                    $nextmodel_table_name = strtolower($next_model) . 'table';
                    $final_alias_table = $relation_table_name . '_' . strtolower($next_model);

                    if (!isset($join_alias_names[$final_alias_table])) {
                        $join_alias_names[$final_alias_table] = 1;

                        $join_SQL = $join_SQL
                            . ' LEFT JOIN ' . $nextmodel_table_name . ' as ' . $final_alias_table
                            . ' on ' . $first_alias_table . '.' . (strtolower($next_model).'_id') . '=' . $final_alias_table . '.id';
                    }
                } else {
                    // single selection
                    $nextmodel_table_name = strtolower($next_model) . 'table';
                    $final_alias_table = strtolower($current_model) . '_' . strtolower($current_field) . '_' . strtolower($next_model);

                    if (!isset($join_alias_names[$final_alias_table])) {
                        $join_alias_names[$final_alias_table] = 1;

                        $join_SQL = $join_SQL
                            . ' LEFT JOIN ' . $nextmodel_table_name . ' as ' . $final_alias_table
                            . ' on ' . $first_alias_table . '.' . $current_field . '=' . $final_alias_table . '.id';
                    }
                }

                $this->setFieldsAndJoinSQL($fields_SQL, $join_SQL, $join_alias_names, $final_alias_table, $final_alias_table, $function, $propertyParts, $field_alias);
            }
        }
    }

    public function is_field_multiple_selection($model, $property) {
        $objectWisilo = new Wisilo();
        $property_list = $objectWisilo->getModelPropertyList($model);
        
        foreach ($property_list as $property_data) {
            if ($property == $property_data['name']) {
                if ('class_selection_multiple' == $property_data['type']) {
                    return true;
                }
            }
        }

        return false;
    }

    public function is_field_file($model, $property) {
        $objectWisilo = new Wisilo();
        $property_list = $objectWisilo->getModelPropertyList($model);
        
        foreach ($property_list as $property_data) {
            if ($property == $property_data['name']) {
                if ('image' == $property_data['type']) {
                    return true;
                }

                if ('file' == $property_data['type']) {
                    return true;
                }
            }
        }

        return false;
    }

    public function setWhereSQL(&$where_SQL, $searchable_fields, $search_text, $conditions) {
        $objectWisiloWidgetHelper = new WisiloWidgetHelper();
        $search_text = $objectWisiloWidgetHelper->convertCustomVariableNameToIdSyntax($search_text);

        $searchSQL = '';
        foreach ($searchable_fields as $field_alias) {
            if ('' != $searchSQL) {
                $searchSQL = $searchSQL . ' OR ';
            }

            $searchSQL = $searchSQL . ' (' . $field_alias . ' like \'%' . $search_text . '%\')';
        }

        
        $objectWisiloWidgetHelper->__setWhereSQL($where_SQL, $searchSQL, $conditions);
    }

    public function setOrderSQL(&$order_SQL, $field_aliases, $orders) {
        $orderSQL = '';

        foreach ($orders as $order_data) {
            if ('' != $orderSQL) {
                $orderSQL .= ',';
            }
            
            $orderSQL = $orderSQL . ' ' . $field_aliases[$order_data['field_guid']] . ' ' . $order_data['type'];
        }

        if ('' != $orderSQL) {
            $order_SQL = ' ORDER BY ' . $orderSQL;
        }
    }

    public function setPaginationSQL(&$pagination_SQL, $paginationData) {
        $objectWisiloWidgetHelper = new WisiloWidgetHelper();
        $pagination_SQL = '';

        $page = isset($paginationData['page']) ? $paginationData['page'] : 0;

        if (strpos($page, "{{CustomVariables/") === 0) {
            $page = $objectWisiloWidgetHelper->convertCustomVariableNameToIdSyntax($page);
            $pagination_SQL = ' LIMIT ' . $page;
        } else {
            $pagination_SQL = ' LIMIT ' . intval($page);
        }

        $records_per_page = isset($paginationData['records_per_page']) ? $paginationData['records_per_page'] : 50;

        if (strpos($records_per_page, "{{CustomVariables/") === 0) {
            $records_per_page = $objectWisiloWidgetHelper->convertCustomVariableNameToIdSyntax($records_per_page);
            $pagination_SQL = $pagination_SQL . ',' . $records_per_page;
        } else {
            $pagination_SQL = $pagination_SQL . ',' . intval($records_per_page);
        }
    }

    public function get_filter_options(Request $request) {    
        $data = [];

        $parameters = $request->route()->parameters();

        $pagename = isset($parameters['pagename'])
                ? htmlspecialchars($parameters['pagename'])
                : '';

        // Widget Pages
        $objectList = WisiloLayout::where('deleted', 0)->groupBy('pagename')->orderBy('pagename', 'asc')->get();

        $list = [];
        $index = 0;

        $list[$index]['id'] = '__global';
        $list[$index]['text'] = 'Global Widgets';
        $index++;

        foreach ($objectList as $object) {
            if ($pagename != $object->pagename) {
                $list[$index]['id'] = $object->pagename;
                $list[$index]['text'] = $object->pagename . ' | ' . 'Widgets';

                if (false !== strpos($object->pagename, '-')) {
                    $parts = explode('-', $object->pagename);

                    if (isset($parts[0])) {
                        $parentLayoutId = intval($parts[0]);
                        $list[$index]['text'] = $this->getFilterOptionTitleByParentId($parentLayoutId) 
                            . ' | '  
                            . $object->container_title 
                            . ' | Widgets';
                    }
                }

                $index++;
            }
        } // foreach ($objectList as $object)

        $data['widget_page'] = $list;

        return [
            'options' => $data
        ];
    }

    public function getFilterOptionTitleByParentId($layoutId) {
        $option_title = '';

        if (0 != $layoutId) {
            $objectLayout = WisiloLayout::where('id', $layoutId)->first();
            $option_title = $objectLayout->pagename . ' | ' . $objectLayout->title;
        }

        return $option_title;
    }

    public function get_model_list(Request $request) {    
        /*
        
        ####################TEST#############
        
        */

        /* $objectLayout = WisiloLayout::where('id', 38)->first();
        $data_source = json_decode($objectLayout->data_source_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));
        $data_source_json = $this->getFormattedDataSource($data_source);

        die(); */

        /*
        
        ####################TEST#############
        
        */

        $list = [];
        $index = 0;

        $list[$index]['id'] = '';
        $list[$index]['text'] = __('Please Select');
        $index++;

        $list[$index]['id'] = 'WisiloUser';
        $list[$index]['text'] = 'WisiloUser';
        $index++;

        $list[$index]['id'] = 'WisiloUserGroup';
        $list[$index]['text'] = 'WisiloUserGroup';
        $index++;

        $objectWisilo = new Wisilo();
		$Models = $objectWisilo->getModelList();
		$countModels = count($Models);

		for ($i=0; $i < $countModels; $i++) {
            $model = $Models[$i];

            $list[$index]['id'] = $model;
            $list[$index]['text'] = $model;
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
        
        $properties = [];
        $index = 0;
        $objectWisilo = new Wisilo();
        $property_list = $objectWisilo->getModelPropertyList($model);
        
        foreach ($property_list as $property_data) {
            $id = $model . '/' . $property_data['name'];
            $text = $model . '/' . $property_data['title'];

            $index = count($properties);
            $properties[$index]['id'] = $id;
            $properties[$index]['text'] = $text;

            if (('class_selection_single' == $property_data['type']) || ('class_selection_multiple' == $property_data['type'])) {
                $this->setPropertyChildrenProperties($properties, $id, $text, $property_data['belongs_to']);
            }
        }

        return [
            'list' => $properties
        ];

    }

    public function setPropertyChildrenProperties(&$properties, $id_prefix, $text_prefix, $model) {
        $objectWisilo = new Wisilo();
        $property_list = $objectWisilo->getModelPropertyList($model);

        foreach ($property_list as $property_data) {
            $id = $id_prefix . '/' . $model . '/' . $property_data['name'];
            $text = $text_prefix . '/' . $property_data['title'];

            $index = count($properties);
            $properties[$index]['id'] = $id;
            $properties[$index]['text'] = $text;
            
            if (('class_selection_single' == $property_data['type']) || ('class_selection_multiple' == $property_data['type'])) {
                $properties[$index]['children'] = $this->setPropertyChildrenProperties($properties, $id, $text, $property_data['belongs_to']);
            }
        }
    }

    public function get_queryresult_by_simple_calculation($query) {
        $calculationResult = [];
        $condition = 1;

        $data_pagination = $this->getPaginationDataFromSQL($query);
        
        try {
            $connection = DB::connection()->getPdo();
        } catch (PDOException $e) {
            print($e->getMessage());
        }
                

        $objPDO = $connection->prepare($query);
        $objPDO->execute();
        $data = $objPDO->fetchAll();

        if (isset($data)) {
            $calculationResult = $data;
        }

        return array($calculationResult, $data_pagination);
    }

    public function getPaginationDataFromSQL($query) {
        $data_pagination = [
            'current_page' => 0,
            'last_page' => 0,
            'per_page' => 0,
            'from' => 0,
            'to' => 0,
            'total' => 0,
            'next_page_url' => '',
            'prev_page_url' => '',
            /* 'show_pagination' => $show_pagination, */
        ];

        
        $queryParts = preg_split("/LIMIT/i", $query);
        
        if (1 == count($queryParts)) {
            return $data_pagination;
        }

        $total_record_count = 0;
        $unlimitedQuery = $queryParts[0];

        try {
            $connection = DB::connection()->getPdo();
        } catch (PDOException $e) {
            print($e->getMessage());
        }
                

        $objPDO = $connection->prepare($unlimitedQuery);
        $objPDO->execute();
        $data = $objPDO->fetchAll();

        if (isset($data)) {
            $total_record_count = count($data);
        }

        $data_pagination['total'] = $total_record_count;
        $limitParts = explode(',', trim($queryParts[1]));
        $per_page = intval($limitParts[1]);
        $per_page = (0 == $per_page) ? 1 : $per_page;
        $current_page = intval($limitParts[0] / $per_page) + 1;

        $data_pagination['current_page'] = $current_page;
        $data_pagination['per_page'] = $per_page;

        $data_pagination['from'] = (($current_page - 1) * $per_page) + 1;
        $data_pagination['to'] = ($current_page * $per_page);

        $data_pagination['last_page'] = ceil($total_record_count / $per_page);

        return $data_pagination;
    }

    public function get_queryresult_by_advanced_calculation($query, $fields) {
        $calculationResult = [];
        $condition = 1;

        $data_pagination = $this->getPaginationDataFromSQL($query);

        try {
            $connection = DB::connection()->getPdo();
        } catch (PDOException $e) {
            print($e->getMessage());
        }
              
        $objPDO = $connection->prepare($query);
        $objPDO->execute();
        $data = $objPDO->fetchAll();

        if (isset($data)) {
            $last_row_index = count($data) - 1;

            foreach ($data as $index => $row_data) {
                foreach ($fields as $field_data) {
                    $customVariableId = $field_data['customvariable'];
                    $variable_name = 'customvariable' . $customVariableId;
                    $column_name = $field_data['field'];

                    $calculationResult[$index][$variable_name] = isset($row_data[$column_name])
                        ? $row_data[$column_name]
                        : '';

                    if ($last_row_index == $index) {
                        $this->setCustomVariableLastValue($customVariableId, $calculationResult[$index][$variable_name]);
                    }
                }

                $calculationResult[$index]['id'] = isset($row_data['id'])
                        ? $row_data['id']
                        : $index;
            }
        }

        return array($calculationResult, $data_pagination);
    }

    public function setCustomVariableLastValue($customVariableId, $variableValue) {
        $customvariable_key = 'customvariable' . $customVariableId;
        $remember = false;
        $remember_type = '';

        $objectVariable = WisiloCustomVariable::where('deleted', 0)
            ->where('id', $customVariableId)
            ->first();

        if (null !== $objectVariable) {
            $remember = true;
            $remember_type = $objectVariable->remember_type;
        }

        if ($remember) {
            if ('session' == $remember_type) {
                session()->put($customvariable_key, $variableValue);
            } else if ('database' == $remember_type) {
                $object = WisiloCustomVariableValue::where('deleted', 0)
                    ->where('customvariable_id', $objectVariable->id)
                    ->where('wisilousergroup_id', $objectVariable->wisilousergroup_id)
                    ->first();
    
                if (null !== $object) {
                    $object->value = $variableValue;
                    $object->save();
                } else {
                    $object = new WisiloCustomVariableValue();
                    $object->customvariable_id = $objectVariable->id;
                    $object->wisilousergroup_id = $objectVariable->wisilousergroup_id;
                    $object->value = $variableValue;
                    $object->save();
                }
            }
        }

        return;
    }

    public function get_infobox_data(Request $request)
    {   
        $parameters = $request->route()->parameters();
        $infobox_data = [];

        $widgetParametersEncoded = isset($parameters['parameters'])
                ? htmlspecialchars($parameters['parameters'])
                : '';

        $objectWisiloWidgetHelper = new WisiloWidgetHelper();
        $widgetParameters = json_decode(base64_decode($widgetParametersEncoded), (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));

        if (!isset($widgetParameters['layout_id'])) {
            return [];
        }

        $layoutId = $widgetParameters['layout_id'];
        $url_parameters = $widgetParameters['url_parameters'];
        $request_parameters = $widgetParameters['request_parameters'];
        $external_parameters = isset($widgetParameters['external_parameters']) ? $widgetParameters['external_parameters'] : [];
        $custom_variables = $widgetParameters['custom_variables'];

        $objectLayout = WisiloLayout::where('id', $layoutId)->first();

        $dependant_customvariables = [];

        if (null !== $objectLayout) {
            $dependant_customvariables = $this->getDependantVariables($objectLayout);

            $metaData = json_decode($objectLayout->meta_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));
            $data_source = json_decode($objectLayout->data_source_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));

            if ('simple' == $data_source['calculation_type']) {
                $meta_data = $data_source['meta_data'];
                $query = $this->getParsedValue($meta_data['query'], '', $url_parameters, $request_parameters, $external_parameters, $custom_variables);
                /* list($queryResult, $data_pagination) = $this->get_queryresult_by_simple_calculation($query); */
                list($queryResult, $data_pagination) = $this->get_queryresult_by_simple_calculation($query);
                $data_pagination['show_pagination'] = isset($metaData['show_pagination']) ? intval($metaData['show_pagination']) : 0;

            } else if ('advanced' == $data_source['calculation_type']) {
                $meta_data = $data_source['meta_data'];
                $query = $objectWisiloWidgetHelper->convertCustomVariableNameToIdSyntax($meta_data['query']);
                $query = $this->getParsedValue($query, '', $url_parameters, $request_parameters, $external_parameters, $custom_variables);
                $fields = isset($meta_data['fields']) ? $meta_data['fields'] : [];
                /* $queryResult = $this->get_queryresult_by_advanced_calculation($query, $fields); */
                list($queryResult, $data_pagination) = $this->get_queryresult_by_advanced_calculation($query, $fields);
                $data_pagination['show_pagination'] = isset($metaData['show_pagination']) ? intval($metaData['show_pagination']) : 0;
            }

            $conditionalData = json_decode($objectLayout->conditional_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));
            $index = 0;
            $last_row_index = count($queryResult) - 1;

            foreach ($queryResult as $row) {
                $row['__is_last_row'] = 0;

                if ($index == $last_row_index) {
                    $row['__is_last_row'] = 1;
                }

                foreach ($metaData as $key => $value) {
                    $infobox_data[$key] = $this->getParsedValue($value, $row, $url_parameters, $request_parameters, $external_parameters, $custom_variables);
                }

                foreach ($conditionalData as $conditionData) {
                    $function_name = $conditionData['guid'];
    
                    require_once(storage_path('app/widget/condition/'. $function_name . '.php'));
                        
                    $conditionResultData = $function_name($objectWisiloWidgetHelper, $row, $url_parameters, $request_parameters);
    
                    if ($conditionResultData['success']) {
                        $conditionalFields = $conditionResultData['data'];
                        foreach ($conditionalFields as $index => $fieldData) {
                            $infobox_data[$fieldData->id] = $this->getParsedValue($fieldData->value, $queryResult, $url_parameters, $request_parameters, $external_parameters, $custom_variables);
                        }
                    }
                }
    
                $index++;
            } // foreach ($objectList as $object)
        }

        return [
            'infobox_data' => $infobox_data,
            'dependant_customvariables' => $dependant_customvariables
        ];
    }

    public function get_recordlist_data(Request $request)
    {
        $data_pagination = [
            'current_page' => 0,
            'last_page' => 0,
            'per_page' => 0,
            'from' => 0,
            'to' => 0,
            'total' => 0,
            'next_page_url' => '',
            'prev_page_url' => '',
            'show_pagination' => false,
        ];

        $dependant_customvariables = [];

        $parameters = $request->route()->parameters();

        $widgetParametersEncoded = isset($parameters['parameters'])
                ? htmlspecialchars($parameters['parameters'])
                : '';

        $objectWisiloWidgetHelper = new WisiloWidgetHelper();
        $widgetParameters = json_decode(base64_decode($widgetParametersEncoded), (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));

        if (!isset($widgetParameters['layout_id'])) {
            return [];
        }

        $layoutId = $widgetParameters['layout_id'];
        $url_parameters = $widgetParameters['url_parameters'];
        $request_parameters = $widgetParameters['request_parameters'];
        $external_parameters = isset($widgetParameters['external_parameters']) ? $widgetParameters['external_parameters'] : [];
        $custom_variables = $widgetParameters['custom_variables'];

        $objectLayout = WisiloLayout::where('id', $layoutId)->first();

        $record_list_title = '';
        $column_titles = [];
        $column_variables = [];
        $visible_columns = [];
        $list = [];

        if (null !== $objectLayout) {
            $dependant_customvariables = $this->getDependantVariables($objectLayout);

            $metaData = json_decode($objectLayout->meta_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));
            $data_source = json_decode($objectLayout->data_source_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));

            if ('simple' == $data_source['calculation_type']) {
                $meta_data = $data_source['meta_data'];
                $query = $this->getParsedValue($meta_data['query'], '', $url_parameters, $request_parameters, $external_parameters, $custom_variables);
                /* $queryResult = $this->get_queryresult_by_simple_calculation($query); */
                list($queryResult, $data_pagination) = $this->get_queryresult_by_simple_calculation($query);
                $data_pagination['show_pagination'] = isset($metaData['show_pagination']) ? intval($metaData['show_pagination']) : 0;
            } else if ('advanced' == $data_source['calculation_type']) {
                $meta_data = $data_source['meta_data'];
                $query = $objectWisiloWidgetHelper->convertCustomVariableNameToIdSyntax($meta_data['query']);
                $query = $this->getParsedValue($query, '', $url_parameters, $request_parameters, $external_parameters, $custom_variables);

                $fields = isset($meta_data['fields']) ? $meta_data['fields'] : [];
                /* $queryResult = $this->get_queryresult_by_advanced_calculation($query, $fields); */
                list($queryResult, $data_pagination) = $this->get_queryresult_by_advanced_calculation($query, $fields);
                $data_pagination['show_pagination'] = isset($metaData['show_pagination']) ? intval($metaData['show_pagination']) : 0;
            }

            $record_list_title = $this->getParsedValue($metaData['record_list_title'], $queryResult, $url_parameters, $request_parameters, $external_parameters, $custom_variables);

            $columns = $metaData['columns'];
            $index = 0;
            foreach ($columns as $column) {
                $objectColumn = (object) $column;

                if ('on' == $objectColumn->visible) {
                    $column_name = $objectColumn->name;

                    $visible_columns[$column_name] = $objectColumn;
                    
                    $column_titles[$index] = $this->getParsedValue(
                        $objectColumn->title, 
                        $queryResult, 
                        $url_parameters, 
                        $request_parameters, 
                        $external_parameters, 
                        $custom_variables
                    );
                    
                    $column_variables[$index] = $column_name;

                    $index++;
                }
            }

            $conditionalData = json_decode($objectLayout->conditional_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));

            $index = 0;
            $last_row_index = count($queryResult) - 1;

            foreach ($queryResult as $row) {
                $list[$index] = [];
                $list[$index]['id'] = $row['id'];
                $list[$index]['displaytexts'] = [];
                $list[$index]['styles'] = [];

                $row['__is_last_row'] = 0;

                if ($index == $last_row_index) {
                    $row['__is_last_row'] = 1;
                }
                
                foreach ($column_titles as $title_index => $column_title) {
                    $column_titles[$title_index] = $this->getParsedValue($column_title, $row, $url_parameters, $request_parameters, $external_parameters, $custom_variables);
                }

                foreach ($column_variables as $column_name) {
                    $objectColumn = $visible_columns[$column_name];
                    $list[$index]['displaytexts'][] = $this->getParsedValue($objectColumn->value, $row, $url_parameters, $request_parameters, $external_parameters, $custom_variables);
                    $list[$index]['styles'][] = $objectColumn->style;
                }

                foreach ($conditionalData as $conditionData) {
                    $function_name = $conditionData['guid'];
    
                    require_once(storage_path('app/widget/condition/'. $function_name . '.php'));
                        
                    $conditionResultData = $function_name($objectWisiloWidgetHelper, $row, $url_parameters, $request_parameters);
    
                    if ($conditionResultData['success']) {
                        $conditionalFields = $conditionResultData['data'];
    
                        foreach ($conditionalFields as $fieldData) {
                            if ('record_list_title' == $fieldData->id) {
                                $record_list_title = $this->getParsedValue($fieldData->value, $row, $url_parameters, $request_parameters, $external_parameters, $custom_variables);
                            } else if ('array' == $fieldData->type) {
                                $list[$index]['displaytexts'] = [];
                                $list[$index]['styles'] = [];

                                $columns = $fieldData->items;
    
                                foreach ($columns as $column) {
                                    $column_name = $column->name;
                                    if (isset($visible_columns[$column_name])) {
                                        $list[$index]['displaytexts'][] = $this->getParsedValue($column->value, $row, $url_parameters, $request_parameters, $external_parameters, $custom_variables);
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

        /* $current_page = $objectList->currentPage();
        $last_page = $objectList->lastPage();
        $per_page = $objectList->perPage();
        $from = (($current_page - 1) * $per_page) + 1;
        $to = ($current_page * $per_page);
        $total = $objectList->total();
        $next_page_url = ($last_page == $current_page) ? null : 'get_recordlist?p=' . ($current_page + 1);
        $prev_page_url = (1 == $current_page) ? null : 'get_recordlist?p=' . ($current_page - 1); */

        return [
            'record_list_title' => $record_list_title,
            'table_header' => [
                'titles' => $column_titles,
                'variables' => $column_variables
            ],
            'list' => $list,
            'data_pagination' => $data_pagination,
            'dependant_customvariables' => $dependant_customvariables
        ];
    }

    public function getDependantVariables($objectLayout) {
        $variables = [];
        $variableIds = [];

        $content = $objectLayout->meta_data_json . ' ' . $objectLayout->data_source_json . ' ' . $objectLayout->conditional_data_json;
        $currentUser = auth()->guard('wisilouser')->user();
        $objectWisiloWidgetHelper = new WisiloWidgetHelper();
        $objectWisilo = new Wisilo();

        $parsed = $objectWisilo->getStringBetween($content, '{{', '}}');

        while (strlen($parsed) > 0) {
            $parsedWithMustache = '{{' . $parsed . '}}';
			$textPart = explode('\/', $parsed);

            if ('CustomVariables' == $textPart[0]) {
                $__key = $textPart[1];
                $objCustomVariable = $objectWisiloWidgetHelper->getCustomVariableByName($__key, $currentUser->wisilousergroup_id);

                if (null !== $objCustomVariable) {
                    $variableIds[$objCustomVariable->id] = 1;
                }
            }
            
            $content = str_replace($parsedWithMustache, '', $content);
			$temp_text = $content;
			$parsed = $objectWisilo->getStringBetween($temp_text, '{{', '}}');
		} // while (strlen($parsed) > 0) {

        if (count($variableIds) > 0) {
            $variables = array_keys($variableIds);
            sort($variables);
        }
        
        return $variables;
    }

    public function get_query_result(Request $request)
    {
        $data_pagination = [
            'current_page' => 0,
            'last_page' => 0,
            'per_page' => 0,
            'from' => 0,
            'to' => 0,
            'total' => 0,
            'next_page_url' => '',
            'prev_page_url' => '',
            'show_pagination' => false,
        ];

        $dependant_customvariables = [];

        $parameters = $request->route()->parameters();

        $widgetParametersEncoded = isset($parameters['parameters'])
                ? htmlspecialchars($parameters['parameters'])
                : '';

        $objectWisiloWidgetHelper = new WisiloWidgetHelper();
        $widgetParameters = json_decode(base64_decode($widgetParametersEncoded), (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));

        if (!isset($widgetParameters['layout_id'])) {
            return [];
        }/* echo '<div style="display:block">';
        echo '<pre>';
        print_r($widgetParameters);
        echo '</pre>';
        echo '</div>';
        die(); */
        

        $layoutId = $widgetParameters['layout_id'];
        $url_parameters = $widgetParameters['url_parameters'];
        $request_parameters = $widgetParameters['request_parameters'];
        $external_parameters = isset($widgetParameters['external_parameters']) ? $widgetParameters['external_parameters'] : [];
        $custom_variables = $widgetParameters['custom_variables'];

        $objectLayout = WisiloLayout::where('id', $layoutId)->first();

        $record_list_title = '';
        $column_titles = [];
        $column_variables = [];
        $visible_columns = [];
        $list = [];

        if (null !== $objectLayout) {
            $dependant_customvariables = $this->getDependantVariables($objectLayout);

            $metaData = json_decode($objectLayout->meta_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));
            $data_source = json_decode($objectLayout->data_source_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));

            if ('simple' == $data_source['calculation_type']) {
                $meta_data = $data_source['meta_data'];
                $query = $this->getParsedValue($meta_data['query'], '', $url_parameters, $request_parameters, $external_parameters, $custom_variables);
                /* echo $query;
                die(); */
                /* $queryResult = $this->get_queryresult_by_simple_calculation($query); */
                list($queryResult, $data_pagination) = $this->get_queryresult_by_simple_calculation($query);
                $data_pagination['show_pagination'] = isset($metaData['show_pagination']) ? intval($metaData['show_pagination']) : 0;
            } else if ('advanced' == $data_source['calculation_type']) {
                $meta_data = $data_source['meta_data'];
                $query = $objectWisiloWidgetHelper->convertCustomVariableNameToIdSyntax($meta_data['query']);
                $query = $this->getParsedValue($query, '', $url_parameters, $request_parameters, $external_parameters, $custom_variables);

                $fields = isset($meta_data['fields']) ? $meta_data['fields'] : [];
                /* $queryResult = $this->get_queryresult_by_advanced_calculation($query, $fields); */
                list($queryResult, $data_pagination) = $this->get_queryresult_by_advanced_calculation($query, $fields);
                $data_pagination['show_pagination'] = isset($metaData['show_pagination']) ? intval($metaData['show_pagination']) : 0;
            }

            foreach ($queryResult as $index => $row) {
                $list[$index] = $row;
            } // foreach ($queryResult as $index => $row) {
        }

        return [
            'list' => $list,
            'data_pagination' => $data_pagination,
            'dependant_customvariables' => $dependant_customvariables
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

        $objectWisilo = new Wisilo();

        $dateFormat = $objectWisilo->getConfigParameterValue('wisilo.generalsettings.dateformat');
        $yearMonthFormat = $objectWisilo->getConfigParameterValue('wisilo.generalsettings.yearmonthformat');
        
        $modelNameWithNamespace = $objectWisilo->getModelNameWithNamespace($model);

        $Widgets = $objectWisilo->getPageLayout($pageName);
        $graphProperties = $objectWisilo->getRecordGraphProperties($Widgets, $model);
        
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

    public function getParsedValue($display_text, $queryResult, $url_parameters, $request_parameters, $external_parameters, $custom_variables)
    {
        $currentUser = auth()->guard('wisilouser')->user();
        $objectWisiloWidgetHelper = new WisiloWidgetHelper();
        $objectWisilo = new Wisilo();
        $parsed = $objectWisilo->getStringBetween($display_text, '{{', '}}');

		while (strlen($parsed) > 0) {
            $parsedWithMustache = '{{' . $parsed . '}}';
			$partResult = '';

			$textPart = explode('/', $parsed);

            if ('__custom_variable__' == $textPart[0]) {
                $id = $textPart[1];
                $partResult = isset($custom_variables[$id]) 
                    ? $custom_variables[$id]
                    : '';
            } else if ('CustomVariables' == $textPart[0]) {
                $__key = $textPart[1];
                $customVariableValue = $objectWisiloWidgetHelper->getCustomVariableValue(
                        $currentUser->wisilousergroup_id, 
                        $__key, 
                        $queryResult, 
                        $url_parameters, 
                        $request_parameters,
                        $external_parameters,
                        $custom_variables
                );
                
				$partResult = ('' != $customVariableValue)
                    ? $customVariableValue
                    : $__key;

            } else if ('GlobalParameters' == $textPart[0]) {
                $__key = $textPart[1];
                if ($__key == '__storage_url') {
                    $partResult = asset('storage/')/*  . '/' */;
                } else {
                    $partResult = $objectWisilo->getConfigParameterValue($__key);
                }
                
            } else if ('UserParameters' == $textPart[0]) {
                $__key = $textPart[1];
                $partResult = $objectWisilo->getUserConfigParameterValue($__key, 'user', $currentUser->id);
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
            } else if ('ExternalParameters' == $textPart[0]) {
                $__key = $textPart[1];
                $partResult = isset($external_parameters[$__key]) 
                    ? $external_parameters[$__key]
                    : $__key;
            } else if ('QueryResultFields' == $textPart[0]) {
                $__key = $textPart[1];
                $partResult = isset($queryResult[$__key]) 
                    ? $queryResult[$__key] 
                    : $__key;
            } else {
                $__key = $parsed;
                $partResult = isset($queryResult[$__key]) 
                    ? $queryResult[$__key] 
                    : $__key;
            }

            
            $display_text = str_replace($parsedWithMustache, $partResult, $display_text);
			$temp_text = $display_text;
			$parsed = $objectWisilo->getStringBetween($temp_text, '{{', '}}');
		} // while (strlen($parsed) > 0) {

        return htmlspecialchars_decode($display_text);
    }

    public function get_layout_page_options(Request $request) {
        $list = [];
        $index = 0;

        $list[$index]['id'] = 'home';
        $list[$index]['text'] = 'Home';
        $index++;

        $objectWisilo = new Wisilo();
		$Models = $objectWisilo->getModelList();
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

        $User = auth()->guard('wisilouser')->user();

        $parameters = $request->route()->parameters();

        $pagename = isset($parameters['pagename'])
                ? htmlspecialchars($parameters['pagename'])
                : '';

        $usergroup_id = $User->wisilousergroup_id;

        $objectWisilo = new Wisilo();
        $objectWisiloMetas = $objectWisilo->getMetaData('__usergroup_layout', $usergroup_id);
        
        if (count($objectWisiloMetas) > 0) {
            $objectWisiloMeta = $objectWisiloMetas[0];
            
            $metaData = json_decode(
                $objectWisilo->base64Decode($objectWisiloMeta->meta_value),
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
