<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEMedia;
use App\AdminLTE\AdminLTEUser;
use App\Http\Requests\AdminLTE\API\MediaPOSTRequest;
use Storage;

class AdminLTEMediaController extends Controller
{
    public function get_recordlist(Request $request)
    {
        $User = auth()->guard('adminlteuser')->user();
        $parameters = $request->route()->parameters();

        $objectAdminLTE = new AdminLTE();
        $dateFormat = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.dateformat');
        $timeFormat = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.timeformat');
        
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

        $limit = 10;
        $onlylastrecord = false;
        $show_pagination = true;

        if ($onlylastrecord) {
            $page = 1;
            $show_pagination = false;
        }
        
        $list = [];
        $current_page = 0;
        $last_page = 0;
        $per_page = 0;
        $from = 0;
        $to = 0;
        $total = 0;
        $next_page_url = null;
        $prev_page_url = null;

        $objectList = AdminLTEMedia::defaultQuery($search_text, $sort_variable, $sort_direction)->paginate($limit, ['*'], 'page', $page);

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
                $list[$index] = array();
                $list[$index]['id'] = $object->id;
                $list[$index]['user_can_create'] = $User->can('create', $object);
                $list[$index]['user_can_read'] = $user_can_view;
                $list[$index]['user_can_update'] = $User->can('update', $object);
                $list[$index]['user_can_delete'] = $User->can('delete', $object);
                $list[$index]['user_can_view'] = $User->can('viewAny', $object);


                $list[$index]['group'] = $object->group;
                $list[$index]['guid'] = $object->guid;
                $list[$index]['file_title'] = $object->file_title;
                $list[$index]['file_name'] = $object->file_name;
                $list[$index]['description'] = $object->description;
                $list[$index]['mime_type'] = $object->mime_type;
                $list[$index]['file_size'] = $object->file_size;
                $list[$index]['file_type'] = $object->file_type;

                // $list[$index]['file'] = $object->file;

                $index++;
            }
        } // foreach ($objectList as $object)

        $data = [
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

    public function post_media(Request $request)
    {
        $User = auth()->guard('adminlteuser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $id = intval($request->input('id'));

        if ($id > 0) {
            $object = AdminLTEMedia::find($id);
            if (!$User->can('update', $object)) {
                $has_error = true;
                $error_msg = __('You can not update this object. Contact your system administrator for more information.');
            }
        } else {
            $object = new AdminLTEMedia();
            if (!$User->can('create', AdminLTEMedia::class)) {
                $has_error = true;
                $error_msg = __('You can not create any object. Contact your system administrator for more information.');
            }
        } // if ($id > 0) {

        if ($has_error) {
            $return_data['id'] = $id;
            $return_data['has_error'] = $has_error;
            $return_data['error_msg'] = $error_msg;

            return $return_data;
        }
        
        $object->deleted = 0;
        if ($id > 0) {
            $object->updated_by = $User->id;
        } else {
            $object->created_by = $User->id;
            $object->updated_by = $User->id;
        } // if ($id > 0) {

        $object->group = $request->input('group');
        $object->guid = $request->input('guid');
        $object->file_title = $request->input('file_title');
        $object->description = $request->input('description');
        
        $object->file_name = $request->input('file_name');
        $object->mime_type = $request->input('mime_type');
        $object->file_size = $request->input('file_size');
        $object->file_type = $request->input('file_type');
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $object->file_name = $file->getClientOriginalName();
            $object->mime_type = $file->getMimeType();
            $object->file_size = $file->getSize();
            $object->file = base64_encode(file_get_contents($file->getRealPath()));
            $object->file_type = '';
        }
        
        $object->save();

        $return_data['id'] = $object->id;
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

    public function post_bulkupload(Request $request)
    {
        $User = auth()->guard('adminlteuser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $guid_csv = $request->input('guid_csv');

        if ('' != $guid_csv) {
            $guids = explode(',' , $guid_csv);

            foreach ($guids as $guid) {
                if ($request->hasFile($guid)) {
                    $file = $request->file($guid);
                    $this->saveFile($guid, $file);
                }
            }
        }

        $return_data['id'] = 1;
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

    private function saveFile($guid, $file) {
        $User = auth()->guard('adminlteuser')->user();

        $object = AdminLTEMedia::where('deleted', 0)->where('guid', $guid)->first();

        if (null === $object) {
            $object = new AdminLTEMedia();
            $object->created_by = $User->id;
            $object->updated_by = $User->id;
        } else {
            $object->updated_by = $User->id;
        } // if (null === $object) {

        $file_name = $file->getClientOriginalName();
        
        $temp = explode('.', $file_name);
        $file_title = $temp[0];

        $object->group = '';
        $object->guid = $guid;
        $object->file_title = $file_title;
        $object->file_name = $file_name;
        $object->mime_type = $file->getMimeType();
        $object->file_size = $file->getSize();
        $object->file = base64_encode(file_get_contents($file->getRealPath()));
        $object->file_type = '';
        $object->save();
    }

    public function delete_files(Request $request) {
        $User = auth()->guard('adminlteuser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $hard_delete = $request->input('hard_delete');
        $selected_ids = $request->input('selected_ids');
        
        $objects = AdminLTEMedia::where('deleted', false)
                ->whereIn('id', $selected_ids)
                ->get();

        /* foreach ($objects as $object)
        {
            if (!$User->can('delete', $object)) {
                $has_error = true;
                $error_msg = __('You can not delete this object. Contact your system administrator for more information.');
                break;
            }              
        } // foreach ($objects as $object) */

        if (!$has_error) {
            foreach ($objects as $object)
            {
                if (1 == $hard_delete) {
                    $object->delete();
                } else {
                    $object->deleted = 1;
                    $object->updated_by = $User->id;
                    $object->save();
                }
            } // foreach ($objects as $object)
        }
            
        $return_data['has_error'] = false;
        $return_data['error_msg'] = $error_msg;

        return $return_data;
    }

    public function download_file(Request $request) {
        $parameters = $request->route()->parameters();

        $file_id = isset($parameters['file_id'])
            ? htmlspecialchars($parameters['file_id'])
            : '';

        if ('' == $file_id) {
            header('HTTP/1.0 404 Not Found');
            header('Status: 404 Not Found');
            die();
        } // if (0 == $id) {

        $item = AdminLTEMedia::where('id', $file_id)
            ->where('deleted', 0)
            ->first();

        if (is_null($item)) {
            header('HTTP/1.0 404 Not Found');
            header('Status: 404 Not Found');
            die();
        }

        $file_contents = base64_decode($item->file);

        $response = response($item->file)
            ->header('Cache-Control', 'no-cache private')
            ->header('Content-Description', 'File Transfer')
            ->header('Content-Type', $item->mime_type)
            ->header('Content-length', strlen($file_contents))
            ->header('Content-Disposition', 'attachment; filename=' . $item->file_name)
            ->header('Content-Transfer-Encoding', 'binary');

        return [
            'url' => "data:" . $item->mime_type . ";base64," . $response->content(),
            'filename' => $item->file_name
        ];
    }

    public function get_file_select_options(Request $request) {
        $parameters = $request->route()->parameters();
        $objectList = AdminLTEMedia::where('deleted', 0)->get();

        $list = [];
        $index = 0;

        foreach ($objectList as $object) {
            $list[$index]['id'] = $object->id;
            $list[$index]['text'] = ('' != $object->file_title)
                ? ($object->file_title . ' (' . $object->mime_type . ')')
                : $object->file_name;
            $index++;
        }

        return [
            'list' => $list
        ];
    }
}
