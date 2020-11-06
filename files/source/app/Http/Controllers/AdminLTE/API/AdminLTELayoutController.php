<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\AdminLTE\AdminLTEUserLayout;
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

        echo $widgetJSON;

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

        $pageName = isset($parameters['pageName'])
                ? htmlspecialchars($parameters['pageName'])
                : '';

        $objectAdminLTE = new AdminLTE();

        $Widgets = $objectAdminLTE->getPageLayout($pageName);
        $permissions = $objectAdminLTE->getUserPermissionData();

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
            $list[$index]['href'] = config('adminlte.main_folder') . '/' . $Widget['href'];

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

    public function get_infoboxvalue(Request $request)
    {   
        $parameters = $request->route()->parameters();

        $pageName = isset($parameters['pageName'])
                ? htmlspecialchars($parameters['pageName'])
                : '';
        $model = isset($parameters['model'])
                ? htmlspecialchars($parameters['model'])
                : '';

        $currentWidget = null;
        $objectAdminLTE = new AdminLTE();
        $modelNameWithNamespace = $objectAdminLTE->getModelNameWithNamespace($model);

        $Widgets = $objectAdminLTE->getPageLayout($pageName);
        
        foreach ($Widgets as $Widget) {
            if (('infobox' == $Widget['type']) && ($model == $Widget['model'])) {
                $currentWidget = $Widget;
                break;
            }
        }

        $text = '';
        $href = '';
        $sizeCSV = '12,12,12';
        $icon = 'fas fa-cog';
        $iconbackground = '#17a2b8';

        if($currentWidget) {
            $text = $Widget['text'];
            $href = $Widget['href'];
            $sizeCSV = $Widget['size'];
            $icon = $Widget['icon'];
            $iconbackground = $Widget['iconbackground'];
        }
        
        $sizes = explode(',', $sizeCSV);
        $size = 'col-lg-' . $sizes[0] . ' col-md-' . $sizes[1] . ' col-xs-' . $sizes[2];

        return [
            'id' => 1,
            'model' => $model,
            'text' => $text,
            'href' => $href,
            'size' => $size,
            'icon' => $icon,
            'iconbackground' => $iconbackground,
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

        $dateFormat = config('adminlte.date_format');
        $yearMonthFormat = config('adminlte.year_month_format');
        
        $objectAdminLTE = new AdminLTE();
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
                ->where('created_at', '>=', $fromDate)
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

        $dateFormat = config('adminlte.date_format');
        $timeFormat = config('adminlte.time_format');
        $objectAdminLTE = new AdminLTE();
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

        $user_can_view_page = $User->can('viewAny', $modelNameWithNamespace);
        if ($user_can_view_page) {
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
                    $list[$index]['user_can_view'] = $user_can_view_page;
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
        }
        
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
}