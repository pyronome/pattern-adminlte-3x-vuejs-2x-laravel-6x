<?php

namespace App\Http\Controllers\Wisilo\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloModelOption;
use App\Wisilo\WisiloUser;
use App\Wisilo\WisiloLog;
use Illuminate\Database\Connectors\MySqlConnector;

class WisiloLogController extends Controller
{
    public function get_recordlist(Request $request)
    {
        $User = auth()->guard('wisilouser')->user();

        $parameters = $request->route()->parameters();

        $dateFormat = config('wisilo.date_format');
        $timeFormat = config('wisilo.time_format');
        $objectWisilo = new Wisilo();
        
        $filterEncoded = '';
        $filter_data = [];
        $filter_used = false;
        $filteredIds = [];
        if ($filterEncoded = \Request::get('f')) {
            if ((null !== $filterEncoded) && ('' != $filterEncoded)){
                $filter_data = json_decode(base64_decode($filterEncoded), (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));

                if (!empty($filter_data)) {
                    $result = $this->getFilterResult($filter_data);
                    $filteredIds = $result['filtered_ids'];
                    $filter_used = $result['filter_used'];
                }
            }
        }
        
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

        $limit = 50;
        $onlylastrecord = false;
        $show_pagination = true;
                
        $current_page = 0;
        $last_page = 0;
        $per_page = 0;
        $from = 0;
        $to = 0;
        $total = 0;
        $next_page_url = null;
        $prev_page_url = null;

        if ($filter_used) {
            $objectList = WisiloLog::defaultQuery($search_text, $sort_variable, $sort_direction)->whereIn('id', $filteredIds)->paginate($limit, ['*'], 'page', $page);
        } else {
            $objectList = WisiloLog::defaultQuery($search_text, $sort_variable, $sort_direction)->paginate($limit, ['*'], 'page', $page);
        }

        $current_page = $objectList->currentPage();
        $last_page = $objectList->lastPage();
        $per_page = $objectList->perPage();
        $from = (($current_page - 1) * $per_page) + 1;
        $to = ($current_page * $per_page);
        $total = $objectList->total();
        $next_page_url = ($last_page == $current_page) ? null : 'get_recordlist?p=' . ($current_page + 1);
        $prev_page_url = (1 == $current_page) ? null : 'get_recordlist?p=' . ($current_page - 1);
        
        $list = [];
        $index = 0;
        $user_display_texts = $this->get_user_display_texts();
        $icons = $this->getTypeIcons();

        foreach ($objectList as $object) {
            $list[$index]['id'] = $object->id;
            $list[$index]['deleted'] = $object->deleted;
            $list[$index]['created_at'] = $object->created_at;
            $datetime_format = config('wisilo.date_format') . ' ' . config('wisilo.time_format');
            $list[$index]['created_at_with_time'] = date($datetime_format, strtotime($object->created_at));

            $list[$index]['updated_at'] = $object->updated_at;
            $list[$index]['updated_at_with_time'] = date($datetime_format, strtotime($object->updated_at));

            $list[$index]['user_id'] = $object->user_id;

            $list[$index]['title'] = $object->title;
            $list[$index]['sub_title'] = $object->sub_title;
            $list[$index]['object_id'] = $object->object_id;
            $list[$index]['object_old_values'] = $object->object_old_values;
            $list[$index]['object_new_values'] = $object->object_new_values;
            $list[$index]['message'] = $object->message;
            $list[$index]['type'] = $object->type;

            $list[$index]['record'] = '';
            if (('INSERT' == $object->type) || ('UPDATE' == $object->type) ||('DELETE' == $object->type)) {
                $list[$index]['record'] = $list[$index]['title'] . '#' . $list[$index]['object_id'];
            } else if (('INFO' == $object->type) || ('WARNING' == $object->type) ||('ERROR' == $object->type)) {
                $list[$index]['record'] = $object->title;
            }
            
            $list[$index]['user_text'] = $user_display_texts[$object->user_id];
            $list[$index]['process_time'] = $list[$index]['created_at_with_time'];
            $list[$index]['type_text'] = $list[$index]['type'];
            $list[$index]['type_table_text'] = $icons[$object->type] . ' ' . $list[$index]['type'];

            $index++;
        } // foreach ($objectList as $object)

        $data = ['list' => $list];

        $filter_buttons = $this->generateFilterButtons($filter_data);

        return [
            'filter_data' => $filterEncoded,
            'filter_buttons' => $filter_buttons,
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

    private function getTypeIcons() {
        $icons = [];
        $icons['INSERT'] = '<span class="bg-success logtype-icon"><i class="fas fa-user-plus"></i></span>';
        $icons['UPDATE'] = '<span class="bg-primary logtype-icon"><i class="fas fa-user-edit"></i></span>';
        $icons['DELETE'] = '<span class="bg-secondary logtype-icon"><i class="fas fa-user-times"></i></span>';
        $icons['INFO'] = '<span class="bg-info logtype-icon"><i class="fas fa-info-circle"></i></span>';
        $icons['WARNING'] = '<span class="bg-warning logtype-icon"><i class="fas fa-exclamation-triangle"></i></span>';
        $icons['ERROR'] = '<span class="bg-danger logtype-icon"><i class="fas fa-minus-circle"></i></span>';
        return $icons;
    }

    private function getFilterResult($filter_data) {
        $config = config('database.connections.mysql');
        try {
            $objConnector = new MySqlConnector();
            $connection = $objConnector->connect($config);
        } catch (PDOException $e) {
            return;
        }

        $filter_used = false;
        $filtered_ids = [];
        
        $step_has_any_records = false; 
            // herhangi bir aşamada; filtre uygulanıp kayıt bulunamadıysa, filtre sonucu 0 kayıt olmalı
            // bu değer true olduktan sonra diğer aşamaları uygulamaya gerek kalmıyor

        // filtered_by_default 25,26,27,30,31 olsun
        $result = isset($filter_data['created_at__start__']);
        $result = $result || isset($filter_data['created_at__end__']);
        $result = $result || isset($filter_data['user']);
        $result = $result || isset($filter_data['type']);

        if ($result) {
            $filter_used = true;

            $filtered_by_default = $this->filtered_by_default($connection, $filter_data);

            if (empty($filtered_by_default)) {
                $step_has_any_records = true;
            } else {
                $filtered_ids = $filtered_by_default;
            }
        }

        if ($step_has_any_records) {
            // herhangi bir aşamada kayıt bulunamadıysa sonuç boş olmalı
            $filtered_ids = [];
        }

        return [
            'filtered_ids' => $filtered_ids,
            'filter_used' => $filter_used
        ];
    }

    private function filtered_by_default($connection, $filter_data) {
        $ids = [];
        $baseSQL = 'SELECT DISTINCT id FROM wisilologtable WHERE deleted=0';
        $filterSQL = '';
        
        // Oluşturulma
        if (isset($filter_data['created_at__start__']) && ('' != $filter_data['created_at__start__'])) {
            if ('' != $filterSQL) {
                $filterSQL .= ' and ';
            }

            $filterVal = $filter_data['created_at__start__'];
            $filterSQL = $filterSQL . " created_at >= '"  .  $filterVal . "'";
        }

        if (isset($filter_data['created_at__end__']) && ('' != $filter_data['created_at__end__'])) {
            if ('' != $filterSQL) {
                $filterSQL .= ' and ';
            }

            $filterVal = (strtotime($filter_data['created_at__end__']) + 86400);
            $filterVal = date('Y-m-d', $filterVal);
            $filterSQL = $filterSQL . " created_at < '"  .  $filterVal . "'";
        }

        // User
        if (isset($filter_data['user']) && (!empty($filter_data['user']))) {
            $user_csv = implode(',', $filter_data['user']);

            if ('' != $filterSQL) {
                $filterSQL .= ' and ';
            }

            $filterVal = '(' . $user_csv . ')';
            $filterSQL = $filterSQL . " user_id in " . $filterVal;
        }

        // Type
        if (isset($filter_data['type']) && (!empty($filter_data['type']))) {
            $type_csv = '';
            foreach ($filter_data['type'] as $type) {
                if ('' != $type_csv) {
                    $type_csv .= ' , ';
                }

                $type_csv = $type_csv . "'" . $type . "'";
            }

            if ('' != $filterSQL) {
                $filterSQL .= ' and ';
            }

            $filterVal = '(' . $type_csv . ')';
            $filterSQL = $filterSQL . " type in " . $filterVal;
        }

        $SQLText = $baseSQL;
        if ('' != $filterSQL) {
            $SQLText = $SQLText . ' and ' . $filterSQL;
        }

        $data = $connection->query($SQLText);
        foreach($data as $row) {
            $ids[] = $row['id'];
        }

        return $ids;
    }

    private function generateFilterButtons($filter_data) {
        $date_format = config('wisilo.date_format');
        $btnTemplate = 
        '<a class="btn btn-app btn-filter" data-filter-token="__PROPERTY__" data-filter-value="__VALUE__">'
            . '<span class="badge text-danger"><i class="fas fa-times"></i></span>'
            . '<b>__VALUE_TEXT__</b>'
            . '<span>__PROPERTY_TEXT__</span>'
        . '</a>';

        $filter_buttons = [];
        
        foreach ($filter_data as $key => $value) {
            switch ($key) {
                case 'created_at__start__':
                    $btnHTML = str_replace('__PROPERTY__', $key, $btnTemplate);
                    $btnHTML = str_replace('__VALUE__', $value, $btnHTML);
                    $btnHTML = str_replace('__VALUE_TEXT__', date($date_format, strtotime($value)), $btnHTML);
                    $btnHTML = str_replace('__PROPERTY_TEXT__', ('Oluşturulma Tarihi  (Başlangıç)'), $btnHTML);
                    $filter_buttons[] = $btnHTML;
                    break;
                case 'created_at__end__':
                    $btnHTML = str_replace('__PROPERTY__', $key, $btnTemplate);
                    $btnHTML = str_replace('__VALUE__', $value, $btnHTML);
                    $btnHTML = str_replace('__VALUE_TEXT__', date($date_format, strtotime($value)), $btnHTML);
                    $btnHTML = str_replace('__PROPERTY_TEXT__', ('Oluşturulma Tarihi (Bitiş)'), $btnHTML);
                    $filter_buttons[] = $btnHTML;
                    break;
                case 'user':
                    $user_display_texts = $this->get_user_display_texts();
                    foreach ($value as $user_id) {
                        $btnHTML = str_replace('__PROPERTY__', $key, $btnTemplate);
                        $btnHTML = str_replace('__VALUE__', $user_id, $btnHTML);
                        $btnHTML = str_replace('__VALUE_TEXT__', $user_display_texts[$user_id], $btnHTML);
                        $btnHTML = str_replace('__PROPERTY_TEXT__', 'User', $btnHTML);
                        $filter_buttons[] = $btnHTML;
                    }
                    break;
                case 'type':
                    /* $type_display_texts = $this->get_type_display_texts(); */
                    foreach ($value as $type) {
                        $btnHTML = str_replace('__PROPERTY__', $key, $btnTemplate);
                        $btnHTML = str_replace('__VALUE__', $type, $btnHTML);
                        $btnHTML = str_replace('__VALUE_TEXT__', $type/* _display_texts[$type_id] */, $btnHTML);
                        $btnHTML = str_replace('__PROPERTY_TEXT__', 'Type', $btnHTML);
                        $filter_buttons[] = $btnHTML;
                    }
                    break;
            }
        }

        if (!empty($filter_buttons)) {
            $filter_buttons[] =
                '<a class="btn btn-app btn-clear-filter bg-danger">'
                    . '<i class="fas fa-times"></i>'
                    . 'Clear'
                . '</a>';
        }

        return $filter_buttons;
    }

    private function get_user_display_texts() {
        $objectList = WisiloUser::where('deleted', 0)->get();
        $list = [];
        
        foreach ($objectList as $object) {
            $list[$object->id] = $object->fullname;
        } // foreach ($objectList as $object)

        return $list;
    }

    public function get_filter_options(Request $request) {    
        $data = [];

        // User
        $objectList = WisiloUser::where('deleted', 0)->orderBy('fullname', 'asc')->get();
        $list = [];
        $index = 0;
        foreach ($objectList as $object) {
            $list[$index]['id'] = $object->id;
            $list[$index]['text'] = $object->fullname;
            $index++;
        } // foreach ($objectList as $object)

        $data['user'] = $list;

        // Type
        $list = [];
        $index = 0;
        $list[$index]['id'] = 'INSERT';
        $list[$index]['text'] = 'Insert';
        $index++;
        $list[$index]['id'] = 'UPDATE';
        $list[$index]['text'] = 'Update';
        $index++;
        $list[$index]['id'] = 'DELETE';
        $list[$index]['text'] = 'Delete';
        $index++;

        $data['type'] = $list;

        return [
            'options' => $data
        ];
    }
}