<?php

namespace App\Http\Controllers\Wisilo\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloMedia;
use App\Wisilo\WisiloUser;
use App\Http\Requests\Wisilo\API\MediaPOSTRequest;
use Storage;

class WisiloMediaController extends Controller
{
    function get(Request $request)
    {
        
        /*
        ::must_update:: servis izinleri nasıl kontrol ediliyor ?
        // start: check user get permission
        $directoryName = basename(dirname(__FILE__));
        $fileName = basename(__FILE__);
    
        includeLibrary('adminlte/checkUserGetPermission');
        $permissionResult = checkUserGetPermission($directoryName, $fileName);
    
        if ($permissionResult['error']) {
            $controller->errorCount = 1;
            $controller->lastError = $permissionResult['error_msg'];
            return false;
        }
        // end: check user get permission
        */
 
        $list = array();
        
        $parameters = $request->route()->parameters();

        $model = '';
        if (!isset($parameters['model'])) {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->list = $list;
            $objectHTMLDB->columns = $this->columns;
            $objectHTMLDB->printHTMLDBList();
            return;
        } // if (!isset($parameters['model'])) {
            
        $object_id = '';
        if (!isset($parameters['object_id'])) {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->list = $list;
            $objectHTMLDB->columns = $this->columns;
            $objectHTMLDB->printHTMLDBList();
            return;
        } // if (!isset($parameters['object_id'])) {

        $model = htmlspecialchars($parameters['model']);
        $object_id = intval($parameters['object_id']);

        if (0 == $object_id) {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->list = $list;
            $objectHTMLDB->columns = $this->columns;
            $objectHTMLDB->printHTMLDBList();
            return;
        } // if (0 == $object_id) {
    
        $tablename = strtolower($model) . "__filetable";
        
        $list = array();
        $index = 0;
    
        try {
            $connection = DB::connection()->getPdo();
        } catch (PDOException $e) {
            print($e->getMessage());
        }
                
        $selectSQL = "SELECT * FROM `". $tablename . "` WHERE `object_id`=:object_id ORDER BY file_index;";
        $objPDO = $connection->prepare($selectSQL);
        $objPDO->bindParam(':object_id', $object_id, PDO::PARAM_INT);
        
        $objPDO->execute();
        $data = $objPDO->fetchAll();
    
        foreach($data as $row) {
            $list[$index]["id"] = $row["id"];
            $list[$index]["object_property"] = $row["object_property"];
            $list[$index]["file_name"] = $row["file_name"];
            $list[$index]["path"] = $row["path"];
            $list[$index]["media_type"] = $row["media_type"];
    
            $fileNameTokens = explode('.', $row["file_name"]);
            $list[$index]["extension"] = strtolower(end($fileNameTokens));
    
            $index++;
        }

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }

    public function post(MediaPOSTRequest $request)
    {   
        /* ::must_update:: need validation */

        $target = isset($request['target'])
            ? htmlspecialchars($request['target'])
            : '';

        $target_path = 'public/' . strtolower($target);

        $media_type = isset($request['media_type'])
            ? intval($request['media_type'])
            : 1;


        $file = $request->file('file');
        $fileOriginalName = $file->getClientOriginalName();
        $extension = $file->extension();

        $fileRealName = str_replace('.' . $extension, '', $fileOriginalName);

        $objectWisilo = new Wisilo();
        $filename = $objectWisilo->convertNameToFileName($fileRealName) . '_' . time()  . '.' . $extension;
        
        $path = Storage::putFileAs($target_path, $file, $filename);

        $real_path = str_replace('public/', '', $path);

        $lastInsertedId = $objectWisilo->insertModelPropertyFile($target, $media_type, $filename, $real_path);

        $lastMessage = $lastInsertedId . '#' . $filename . '#' . $real_path;

        return response()->json(['lastMessage'=>$lastMessage, 'errorCount'=>0, 'lastError'=>'']);
    }

    public function get_recordlist(Request $request)
    {
        $User = auth()->guard('wisilouser')->user();
        $parameters = $request->route()->parameters();

        $objectWisilo = new Wisilo();
        $dateFormat = $objectWisilo->getConfigParameterValue('wisilo.generalsettings.dateformat');
        $timeFormat = $objectWisilo->getConfigParameterValue('wisilo.generalsettings.timeformat');
        
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

        $objectList = WisiloMedia::defaultQuery($search_text, $sort_variable, $sort_direction)->paginate($limit, ['*'], 'page', $page);

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
        $User = auth()->guard('wisilouser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $id = intval($request->input('id'));

        if ($id > 0) {
            $object = WisiloMedia::find($id);
            if (!$User->can('update', $object)) {
                $has_error = true;
                $error_msg = __('You can not update this object. Contact your system administrator for more information.');
            }
        } else {
            $object = new WisiloMedia();
            if (!$User->can('create', WisiloMedia::class)) {
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
        $User = auth()->guard('wisilouser')->user();
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
        $User = auth()->guard('wisilouser')->user();

        $object = WisiloMedia::where('deleted', 0)->where('guid', $guid)->first();

        if (null === $object) {
            $object = new WisiloMedia();
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
        $User = auth()->guard('wisilouser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $hard_delete = $request->input('hard_delete');
        $selected_ids = $request->input('selected_ids');
        
        $objects = WisiloMedia::where('deleted', false)
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

        $item = WisiloMedia::where('id', $file_id)
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
        $objectList = WisiloMedia::where('deleted', 0)->get();

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

    public function get_file_contents(Request $request) {
        $parameters = $request->route()->parameters();
        $idcsv = isset($parameters['idcsv'])
            ? htmlspecialchars($parameters['idcsv'])
            : '';

        $ids = explode(',', $idcsv);

        $objectList = WisiloMedia::whereIn('id', $ids)->get();

        $list = [];
        $index = 0;

        foreach ($objectList as $object) {
            $list[$index]['id'] = $object->id;
            $list[$index]['file_title'] = $object->file_title;
            $list[$index]['file_name'] = $object->file_name;
            $list[$index]['description'] = $object->description;
            $list[$index]['is_image'] = false;
            $list[$index]['html'] = '';

            $file_contents = base64_decode($object->file);

            $response = response($object->file)
                ->header('Cache-Control', 'no-cache private')
                ->header('Content-Description', 'File Transfer')
                ->header('Content-Type', $object->mime_type)
                ->header('Content-length', strlen($file_contents))
                ->header('Content-Disposition', 'attachment; filename=' . $object->file_name)
                ->header('Content-Transfer-Encoding', 'binary');

            $url = "data:" . $object->mime_type . ";base64," . $response->content();

            $html = '';

            if (false !== strpos($object->mime_type, 'image')) {
                $list[$index]['is_image'] = true;
                $html = '<img src="' . $url . '">';
            } else {
                $html = '<button type="button" class="text-btn p-0 file_download" data-file-id="' . $object->id . '">'
                        . '<span>' . $object->file_title . '</span>'
                        . '</button>';
            }

            $list[$index]['html'] = $html;
            $index++;
        }

        return [
            'list' => $list
        ];


        /* $image = imagecreatefromstring($blob); 

        ob_start(); //You could also just output the $image via header() and bypass this buffer capture.
        imagejpeg($image, null, 80);
        $data = ob_get_contents();
        ob_end_clean();
        echo '<img src="data:image/jpg;base64,' .  base64_encode($data)  . '" />'; */
    }
}
