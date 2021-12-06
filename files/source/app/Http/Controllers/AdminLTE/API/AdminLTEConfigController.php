<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEModelOption;
use App\AdminLTE\AdminLTEConfig;
use App\AdminLTE\AdminLTEConfigFile;
use App\Http\Requests\AdminLTE\API\AdminLTEConfigPOSTRequest;
use Illuminate\Support\Facades\DB;
use PDO;

class AdminLTEConfigController extends Controller
{
    public function getlist(Request $request)
    {
        $objectAdminLTE = new AdminLTE();

        $User = auth()->guard('adminlteuser')->user();

        $parameters = $request->route()->parameters();

        $search_text = '';
        if ($s = \Request::get('s')) {
            $search_text = $s;
        }

        $page = 1;
        if ($p = \Request::get('p')) {
            $page = $p;
        }

        $sort_variable = 'id';
        $sort_direction = 'asc';

        $limit = 1000;
        $show_pagination = false;

        $current_page = 0;
        $last_page = 0;
        $per_page = 0;
        $from = 0;
        $to = 0;
        $total = 0;
        $next_page_url = null;
        $prev_page_url = null;

        $objectList = AdminLTEConfig::defaultQuery($search_text, $sort_variable, $sort_direction)->paginate($limit, ['*'], 'page', $page);

        $current_page = $objectList->currentPage();
        $last_page = $objectList->lastPage();
        $per_page = $objectList->perPage();
        $from = (($current_page - 1) * $per_page) + 1;
        $to = ($current_page * $per_page);
        $total = $objectList->total();
        $next_page_url = ($last_page == $current_page) ? null : 'getlist?p=' . ($current_page + 1);
        $prev_page_url = (1 == $current_page) ? null : 'getlist?p=' . ($current_page - 1);
                    
        $configList = [];

        foreach ($objectList as $object) {
            $user_can_view = $User->can('view', $object);

            if ($user_can_view && (1 == $object->enabled)) {
                $configList[$object->__key]['object'] = $object;
                $configList[$object->__key]['searched'] = false;
            }
        } // foreach ($objectList as $object)

        $this->setConfigTree($configList);

        $keys = array_keys($configList);
        $list = [];
        $index = 0;

        foreach ($keys as $key) {
            $object = $configList[$key]['object'];

            $list[$index] = array();
            $list[$index]['id'] = $object->id;
            $list[$index]['deleted'] = $object->deleted;
            $list[$index]['created_at'] = $object->created_at;
            $list[$index]['updated_at'] = $object->updated_at;
            $list[$index]['enabled'] = $object->enabled;
            $list[$index]['required'] = $object->required;
            $list[$index]['__order'] = $object->__order;
            $list[$index]['type'] = $object->type;
            $list[$index]['parent'] = $this->getParentKey($object->__key);
            $list[$index]['__key'] = $object->__key;
            $list[$index]['title'] = $object->title;
            $list[$index]['default_value'] = $object->default_value;
            $list[$index]['value'] = $object->value;
            $list[$index]['description'] = $object->description;
            $list[$index]['hint'] = $object->hint;

            $metaData = json_decode($object->meta_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));

            $list[$index]['option_titles'] = isset($metaData['option_titles']) ? $metaData['option_titles'] : '';
            $list[$index]['option_values'] = isset($metaData['option_values']) ? $metaData['option_values'] : '';
            $list[$index]['toggle_elements'] = isset($metaData['toggle_elements']) ? $metaData['toggle_elements'] : [];
            $list[$index]['url'] = isset($metaData['url']) ? $metaData['url'] : '';
            $list[$index]['content'] = isset($metaData['content']) ? $metaData['content'] : '';
            $list[$index]['min'] = isset($metaData['min']) ? $metaData['min'] : 0;
            $list[$index]['max'] = isset($metaData['max']) ? $metaData['max'] : 0;
            $list[$index]['step'] = isset($metaData['step']) ? $metaData['step'] : 0;
            $list[$index]['multiple'] = isset($metaData['multiple']) ? $metaData['multiple'] : 0;
            $list[$index]['file_types'] = isset($metaData['file_types']) ? $metaData['file_types'] : '';

            $list[$index]['level'] = 0;
            if ('group' == $object->type) {
                $list[$index]['level'] = $this->getGroupLevel($object->__key);
            }

            $index++;
        }

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

    public function getGroupLevel($__key) {
        $parts = explode('.', $__key);
        $level = count($parts) - 1;
        return $level;
    }

    public function setConfigTree(&$configList) {
        $need_search = false;
        $list = $configList;
        foreach ($list as $__key => $item) {
            $object = $item['object'];
            $parentKey = $this->getParentKey($object->__key);

             if ( ('' != $parentKey) && isset($configList[$parentKey]) ) {
                $parentObject = $this->getConfigObject($parentKey);

                if ((null !== $parentObject) && (1 == $parentObject->enabled)) {
                    $configList[$parentKey]['object'] = $parentObject;
                    $configList[$parentKey]['searched'] = false;
                    $need_search = true;
                    $configList[$__key]['searched'] = true;
                } else {
                    // clear unabled sub elements
                    $this->cleanUnabledSubelements($configList, $parentKey);
                }
            }
        }

        return;
    }

    public function cleanUnabledSubelements(&$configList, $parentKey) {
        foreach ($configList as $__key => $object) {
            if ($this->startsWith($__key,$parentKey)) {
                unset($configList[$__key]);
            }
        }

        return;
    }

    public function startsWith($string, $startString)
    {
        $len = strlen($startString);
        return (substr($string, 0, $len) === $startString);
    }

    public function get_recordlist(Request $request)
    {
        $parameters = $request->route()->parameters();

        $objectList = AdminLTEConfig::where('deleted', 0)->get();

        $list = [];

        foreach ($objectList as $index => $object) {
            $__key = $object->__key;

            $list[$__key]['enabled'] = $object->enabled;
            $list[$__key]['type'] = $object->type;
            $list[$__key]['title'] = $object->title;
            $list[$__key]['option'] = $this->getParameterOptionTitle($object->__key);
        }

        return [
            'list' => $list
        ];
    }

	public function get(Request $request)
    {    
        $data = [];
        
        $parameters = $request->route()->parameters();

        $id = isset($parameters['id'])
            ? intval($parameters['id'])
            : 0;


        if ($id <= 0) {
            return;
        } // if ($id <= 0) {
        
        // is new ?
        if ('new' == htmlspecialchars($parameters['id'])) {
            return;
        } // if (isset($parameters['id']) && ('new' == htmlspecialchars($parameters['id']))) {

        if ($id > 0) {
            $objectAdminLTEConfig = AdminLTEConfig::where('id', $id)->where('deleted', 0)->first();
        }
        
        $User = auth()->guard('adminlteuser')->user();

        if ($User->can('view', $objectAdminLTEConfig)) {
            $data['user_can_create'] = $User->can('create', AdminLTEConfig::class);
            $data['user_can_read'] = $User->can('view', $objectAdminLTEConfig);
            $data['user_can_update'] = $User->can('update', $objectAdminLTEConfig);
            $data['user_can_delete'] = $User->can('delete', $objectAdminLTEConfig);
            $data['user_can_view'] = $User->can('viewAny', $objectAdminLTEConfig);

            $data['id'] = $objectAdminLTEConfig->id;
            $data['deleted'] = $objectAdminLTEConfig->deleted;
            $data['created_at'] = $objectAdminLTEConfig->created_at;
            $data['updated_at'] = $objectAdminLTEConfig->updated_at;
            $data['enabled'] = $objectAdminLTEConfig->enabled;
            $data['required'] = $objectAdminLTEConfig->required;
            $data['__order'] = $objectAdminLTEConfig->__order;
            $data['type'] = $objectAdminLTEConfig->type;
            $data['parent'] = $this->getParentKey($objectAdminLTEConfig->__key);
            $data['__key'] = $objectAdminLTEConfig->__key;
            $data['title'] = $objectAdminLTEConfig->title;
            $data['option_titles'] = $objectAdminLTEConfig->option_titles;
            $data['option_values'] = $objectAdminLTEConfig->option_values;
            $data['toggle_elements'] = ('' == $objectAdminLTEConfig->toggle_elements) ? [] : explode(',', $objectAdminLTEConfig->toggle_elements);
            $data['url'] = $objectAdminLTEConfig->url;
            $data['content'] = $objectAdminLTEConfig->content;
            $data['min'] = $objectAdminLTEConfig->min;
            $data['max'] = $objectAdminLTEConfig->max;
            $data['step'] = $objectAdminLTEConfig->step;
            $data['default_value'] = $objectAdminLTEConfig->default_value;
            $data['value'] = $objectAdminLTEConfig->value;
        }

        return [
            'object' => $data
        ];
    }

    public function getConfigObject($key) {
        return AdminLTEConfig::where('__key', $key)
            ->where('deleted', 0)
            /* ->where('enabled', 1) */
            ->first();
    }

    public function getParentKey($key) {
        $parent = '';

        if ('' != $key) {
           $parts = explode('.', $key);
           $length = count($parts);
           $base = $parts[$length-1];

           if ($length > 1) {
               $parent = str_replace(('.'.$base), '', $key);
           }
        }

        return $parent;
    }

    public function getBaseKey($key) {
        $basekey = '';

        if ('' != $key) {
           $parts = explode('.', $key);
           $length = count($parts);
           $basekey = $parts[$length-1];
        }

        return $basekey;
    }

	public function post(AdminLTEConfigPOSTRequest $request)
    {
        $User = auth()->guard('adminlteuser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];

        $id = intval($request->input('id'));

        if ($id > 0) {
            $objectAdminLTEConfig = AdminLTEConfig::find($id);
            if (!$User->can('update', $objectAdminLTEConfig)) {
                $has_error = true;
                $error_msg = __('You can not update this object. Contact your system administrator for more information.');
            }
        } else {
            $objectAdminLTEConfig = new AdminLTEConfig();
            if (!$User->can('create', AdminLTEConfig::class)) {
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
        
        $objectAdminLTEConfig->deleted = 0;
        $objectAdminLTEConfig->enabled = ('' != $request->input('enabled'))
                ? intval($request->input('enabled'))
                : 0;
        $objectAdminLTEConfig->required = ('' != $request->input('required'))
                ? intval($request->input('required'))
                : 0;
        $objectAdminLTEConfig->__order = ('' != $request->input('__order'))
                ? intval($request->input('__order'))
                : 0;
        $objectAdminLTEConfig->type = $request->input('type');
       
        $parent = $request->input('parent');
        
        $objectAdminLTEConfig->title = $request->input('title');

        $objectAdminLTE = new AdminLTE();
        $key = $objectAdminLTE->convertTitleToConfigName($objectAdminLTEConfig->title);

        if ('' != $parent) {
            $key = $parent . '.' . $key;
        }

        $objectAdminLTEConfig->__key = $key;


        $objectAdminLTEConfig->option_titles = $request->input('option_titles');
        $objectAdminLTEConfig->option_values = $request->input('option_values');

        $toggle_elements = '';
        if (!empty($request->input('toggle_elements'))) {
            $toggle_elements = implode(',' , $request->input('toggle_elements'));
        }


        $objectAdminLTEConfig->toggle_elements = $toggle_elements;
        $objectAdminLTEConfig->url = $request->input('url');
        $objectAdminLTEConfig->content = $request->input('content');
        $objectAdminLTEConfig->min = floatval($request->input('min'));
        $objectAdminLTEConfig->max = floatval($request->input('max'));
        $objectAdminLTEConfig->step = floatval($request->input('step'));
        $objectAdminLTEConfig->default_value = $request->input('default_value');
        $objectAdminLTEConfig->value = $request->input('value');

		$objectAdminLTEConfig->save();
		





        $return_data['id'] = $objectAdminLTEConfig->id;
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

    public function post_config_data(Request $request)
    {
        $User = auth()->guard('adminlteuser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $config_dataJSON = $request->input('config_data');

        $config_data = json_decode(
            $config_dataJSON,
            (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS)
        );

        $files = [];
        $file_index = 0;

        foreach ($config_data as $element_data) {
            if (isset($element_data['key'])) {
                if ('file' == $element_data['type']) {
                    $files[$file_index]['parameter'] = $element_data['key'];
                    $files[$file_index]['id'] = $element_data['val'];
                } else {
                    $this->saveConfigParameter($element_data);
                }
            }
        }

        foreach ($files as $key => $fileData) {
            if ($request->hasFile($fileData['id'])) {
                $file = $request->file($fileData['id']);
                $this->saveFile($fileData['parameter'], $file);
            }
        }

        $return_data['id'] = 1;
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

    public function saveConfigParameter($element_data)
    {
        $val = '';
        if (isset($element_data['val'])) {
            $val = $element_data['val'];
        }

        $object = $this->getConfigObject($element_data['key']);
        if (null !== $object) {
            $object->value = $val;
            $object->save();
        }
    }

    public function saveFile($parameter, $file) {
        /* //File Name
        echo $file->getClientOriginalName() . '<br>';
    
        //Display File Extension
        echo $file->getClientOriginalExtension() . '<br>';

        //Display File Real Path
        echo $file->getRealPath() . '<br>';

        //Display File Size
        echo $file->getSize() . '<br>';

        //Display File Mime Type
        echo $file->getMimeType() . '<br>'; */
       
        $object = AdminLTEConfigFile::where('deleted', 0)->where('parameter', $parameter)->first();

        if (null === $object) {
            $object = new AdminLTEConfigFile();
        }

        $object->parameter = $parameter;
        $object->file_name = $file->getClientOriginalName();
        $object->mime_type = $file->getMimeType();
        $object->file_size = $file->getSize();
        $object->file = base64_encode(file_get_contents($file->getRealPath()));
        $object->save();

        $configObject = $this->getConfigObject($parameter);
        if (null !== $configObject) {
            $configObject->value = $object->file_name;
            $configObject->save();
        }
    }

    public function download(Request $request) {
        $parameters = $request->route()->parameters();

        $key = isset($parameters['key'])
            ? htmlspecialchars($parameters['key'])
            : '';

        if ('' == $key) {
            header('HTTP/1.0 404 Not Found');
            header('Status: 404 Not Found');
            die();
        } // if (0 == $id) {

        $item = AdminLTEConfigFile::where('parameter', $key)
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

    public function delete(Request $request)
    {
        $User = auth()->guard('adminlteuser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];

        $selected_ids = $request->input('selected_ids');
        
        $objects = AdminLTEConfig::where('deleted', false)
                ->whereIn('id', $selected_ids)
                ->get();

        foreach ($objects as $object)
        {
            if (!$User->can('delete', $object)) {
                $has_error = true;
                $error_msg = __('You can not delete this object. Contact your system administrator for more information.');
                break;
            }              
        } // foreach ($objects as $object)

        if (!$has_error) {
            foreach ($objects as $object)
            {
                $object->deleted = 1;
                $object->save();                
            } // foreach ($objects as $object)
        }

        return $return_data;
    }
    
	public function get_typelist(Request $request) {
        $list = [];
        $index = 0;

        $list[$index]['id'] = 'group';
        $list[$index]['text'] = 'Group';
        $index++;
    
        $list[$index]['id'] = 'toggle';
        $list[$index]['text'] = 'Toggle';
        $index++;
    
        $list[$index]['id'] = 'checkbox';
        $list[$index]['text'] = 'Checkbox';
        $index++;

        $list[$index]['id'] = 'dropdown';
        $list[$index]['text'] = 'Dropdown';
        $index++;
        
        $list[$index]['id'] = 'number';
        $list[$index]['text'] = 'Number';
        $index++;

        $list[$index]['id'] = 'shorttext';
        $list[$index]['text'] = 'Shorttext';
        $index++;

        $list[$index]['id'] = 'textarea';
        $list[$index]['text'] = 'Textarea';
        $index++;

        return [
            'list' => $list
        ];
    }

    public function get_parentlist(Request $request) {
        $parameters = $request->route()->parameters();

        $id = isset($parameters['id'])
            ? intval($parameters['id'])
            : 0;

        $objectList = AdminLTEConfig::where('id', '!=', $id)
            ->where('deleted', 0)
            /* ->where('enabled', 1) */
            ->where('type', 'group')
            ->orderBy('title', 'asc')
            ->get();

        $list = [];

        foreach ($objectList as $index => $object) {
            $list[$index]['id'] = $object->__key;
            $list[$index]['text'] = $this->getParameterOptionTitle($object->__key);
        }

        return [
            'list' => $list
        ];
    }

    public function get_toggle_elements_options(Request $request) {
        $parameters = $request->route()->parameters();

        $id = isset($parameters['id'])
            ? intval($parameters['id'])
            : 0;

        $objectList = AdminLTEConfig::where('id', '!=', $id)
            ->where('deleted', 0)
            ->where('enabled', 1)
            ->orderBy('title', 'asc')
            ->get();

        $list = [];

        foreach ($objectList as $index => $object) {
            $list[$index]['id'] = $object->__key;
            $list[$index]['text'] = $this->getParameterOptionTitle($object->__key);
        }

        return [
            'list' => $list
        ];
    }

    public function getParameterOptionTitle($key) {
        $option_title = '';
        $parts = explode('.', $key);
        $parent_key = '';

        foreach ($parts as $part) {
            if ('' != $parent_key) {
                $parent_key .= '.';
            }

            $parent_key .= $part;

            $title = $this->getElementTitle($parent_key);

            if ('' != $option_title) {
                $option_title .= ' / ';
            }

            $option_title .= $title;
        }

        return $option_title;
    }

    public function getElementTitle($key) {
        $element_title = '';

        $object = AdminLTEConfig::where('deleted', 0)->where('__key', $key)->first();

        if (null !== $object) {
            $element_title = $object->title;
        }

        return $element_title;
    }

    public function get_json() {
        $User = auth()->guard('adminlteuser')->user();

        $AdminLTEConfigList = AdminLTEConfig::where('deleted', 0)->where('parent_id', 0)->get();
		$parent_data = [];
		$index = 0;        

		foreach ($AdminLTEConfigList as $object) {
            $parent_data[$index] = [];
            $parent_data[$index]['system'] = $object->system;
            $parent_data[$index]['locked'] = (1 == $object->locked);
            $parent_data[$index]['owner'] = $object->owner;

            $is_owner = 0;
            if ($User->id == $object->owner) {
                $is_owner = 1;
            }
            $parent_data[$index]['is_owner'] = $is_owner;

            $editable = 0;
            if ((0 == $object->system) && ((0 == $object->locked) || $is_owner)) {
                $editable = 1;
            }
            $parent_data[$index]['editable'] = $editable;

            $parent_data[$index]['enabled'] = (1 == $object->enabled);
            $parent_data[$index]['required'] = (1 == $object->required);
            $parent_data[$index]['__key'] = $object->__key;
            $parent_data[$index]['basekey'] = $this->getBaseKey($object->__key);
            $parent_data[$index]['__parent'] = $this->getParentKey($object->__key);
            $parent_data[$index]['type'] = $object->type;
            $parent_data[$index]['title'] = $object->title;
            $parent_data[$index]['default_value'] = $object->default_value;
            $parent_data[$index]['value'] = $object->value;
            $parent_data[$index]['hint'] = $object->hint;
            $parent_data[$index]['description'] = $object->description;

            $metaData = json_decode($object->meta_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));

            $parent_data[$index]['option_titles'] = isset($metaData['option_titles']) ? $metaData['option_titles'] : '';
            $parent_data[$index]['option_values'] = isset($metaData['option_values']) ? $metaData['option_values'] : '';
            $parent_data[$index]['toggle_elements'] = isset($metaData['toggle_elements']) ? $metaData['toggle_elements'] : [];
            $parent_data[$index]['url'] = isset($metaData['url']) ? $metaData['url'] : '';
            $parent_data[$index]['content'] = isset($metaData['content']) ? $metaData['content'] : '';
            $parent_data[$index]['min'] = isset($metaData['min']) ? $metaData['min'] : 0;
            $parent_data[$index]['max'] = isset($metaData['max']) ? $metaData['max'] : 0;
            $parent_data[$index]['step'] = isset($metaData['step']) ? $metaData['step'] : 0;
            $parent_data[$index]['multiple'] = isset($metaData['multiple']) ? $metaData['multiple'] : 0;
            $parent_data[$index]['file_types'] = isset($metaData['file_types']) ? $metaData['file_types'] : '';

            $children = $this->getChildren($object->id);

            if (0 != count($children)) {
                $parent_data[$index]['children'] = $children;
            }

            $index++;
		}
        
        return $parent_data;
	}

    public function getChildren($parent_id) {
        $User = auth()->guard('adminlteuser')->user();

        $AdminLTEConfigList = AdminLTEConfig::where('deleted', 0)->where('parent_id', $parent_id)->get();
        $children_data = [];
		$index = 0;

		foreach ($AdminLTEConfigList as $object) {
            $children_data[$index] = [];
            $children_data[$index]['system'] = $object->system;
            $children_data[$index]['locked'] = (1 == $object->locked);
            $children_data[$index]['owner'] = $object->owner;

            $is_owner = 0;
            if ($User->id == $object->owner) {
                $is_owner = 1;
            }
            $children_data[$index]['is_owner'] = $is_owner;

            $editable = 0;
            if ((0 == $object->system) && ((0 == $object->locked) || $is_owner)) {
                $editable = 1;
            }
            $children_data[$index]['editable'] = $editable;

            $children_data[$index]['enabled'] = (1 == $object->enabled);
            $children_data[$index]['required'] = (1 == $object->required);
            $children_data[$index]['__key'] = $object->__key;
            $children_data[$index]['basekey'] = $this->getBaseKey($object->__key);
            $children_data[$index]['__parent'] = $this->getParentKey($object->__key);
            $children_data[$index]['type'] = $object->type;
            $children_data[$index]['title'] = $object->title;
            $children_data[$index]['default_value'] = $object->default_value;
            $children_data[$index]['value'] = $object->value;
            $children_data[$index]['hint'] = $object->hint;
            $children_data[$index]['description'] = $object->description;
            
            $metaData = json_decode($object->meta_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));

            $children_data[$index]['option_titles'] = isset($metaData['option_titles']) ? $metaData['option_titles'] : '';
            $children_data[$index]['option_values'] = isset($metaData['option_values']) ? $metaData['option_values'] : '';
            $children_data[$index]['toggle_elements'] = isset($metaData['toggle_elements']) ? $metaData['toggle_elements'] : [];
            $children_data[$index]['url'] = isset($metaData['url']) ? $metaData['url'] : '';
            $children_data[$index]['content'] = isset($metaData['content']) ? $metaData['content'] : '';
            $children_data[$index]['min'] = isset($metaData['min']) ? $metaData['min'] : 0;
            $children_data[$index]['max'] = isset($metaData['max']) ? $metaData['max'] : 0;
            $children_data[$index]['step'] = isset($metaData['step']) ? $metaData['step'] : 0;
            $children_data[$index]['multiple'] = isset($metaData['multiple']) ? $metaData['multiple'] : 0;
            $children_data[$index]['file_types'] = isset($metaData['file_types']) ? $metaData['file_types'] : '';

            $children = $this->getChildren($object->id);;

            if (0 != count($children)) {
                $children_data[$index]['children'] = $children;
            }

            $index++;
		}

        return $children_data;
    }

    public function getSystemConfigData() {
        $AdminLTEConfigList = AdminLTEConfig::where('deleted', 0)->where('system', 1)->get();
		$systemConfigData = [];

		foreach ($AdminLTEConfigList as $object) {
            $systemConfigData[$object->__key] = $object->value;
		}
        
        return $systemConfigData;
    }

	public function post_json(Request $request) {
        /* $systemConfigData = $this->getSystemConfigData(); */

        $User = auth()->guard('adminlteuser')->user();

        $objectAdminLTE = new AdminLTE();
        $config_json = rawurldecode(htmlspecialchars_decode($request->input('config_json')));
        $config_data = json_decode($config_json,
                (JSON_HEX_QUOT
                | JSON_HEX_TAG
                | JSON_HEX_AMP
                | JSON_HEX_APOS));

		AdminLTEConfig::query()->truncate();

        foreach ($config_data as $__order => $data) {
			$AdminLTEConfig = new AdminLTEConfig();

            /* $__key = $objectAdminLTE->convertTitleToConfigName($data['title']);
            $value = $data['value'];
            if (isset($systemConfigData[$__key])) {

            } */

			$AdminLTEConfig->enabled = $data['enabled'];
            $AdminLTEConfig->system = $data['system'];
			$AdminLTEConfig->required = $data['required'];
            $AdminLTEConfig->locked = $data['locked'];

            $owner = 0;
            if (0 == $data['system']) {
                $owner = ($data['owner'] > 0) ? $data['owner'] : $User->id;
            }
            $AdminLTEConfig->owner = $owner;
            
			$AdminLTEConfig->__order = $__order;
			$AdminLTEConfig->parent_id = 0;
			$AdminLTEConfig->type = $data['type'];
			$AdminLTEConfig->title = $data['title'];
            $AdminLTEConfig->default_value = $data['default_value'];
            $AdminLTEConfig->value = $data['value'];
            $AdminLTEConfig->hint = $data['hint'];
            $AdminLTEConfig->description = $data['description'];
            $AdminLTEConfig->__key = $data['basekey'];//$objectAdminLTE->convertTitleToConfigName($AdminLTEConfig->title);

            $metaData = [];
            $metaData['multiple'] = $data['multiple'];
            $metaData['option_titles'] = $data['option_titles'];
            $metaData['option_values'] = $data['option_values'];
            $metaData['toggle_elements'] = empty($data['toggle_elements']) ? [] : $data['toggle_elements'];
            $metaData['url'] = $data['url'];
            $metaData['content'] = $data['content'];
            $metaData['min'] = $data['min'];
            $metaData['max'] = $data['max'];
            $metaData['step'] = $data['step'];
            $metaData['file_types'] = $data['file_types'];

            $encodedData = json_encode($metaData, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
            $AdminLTEConfig->meta_data_json = $encodedData;

			$AdminLTEConfig->save();

			if (isset($data['children'])) {
                $this->saveChildren($data['children'], $AdminLTEConfig->id, $AdminLTEConfig->__key);
			}
		}
	}

    public function saveChildren($children, $parent_id, $parent_key) {
        $User = auth()->guard('adminlteuser')->user();
        $objectAdminLTE = new AdminLTE();

		foreach ($children as $__order => $data) {
            $AdminLTEConfig = new AdminLTEConfig();

			$AdminLTEConfig->enabled = $data['enabled'];
            $AdminLTEConfig->system = $data['system'];
			$AdminLTEConfig->required = $data['required'];
            $AdminLTEConfig->locked = $data['locked'];

            $owner = 0;
            if (0 == $data['system']) {
                $owner = ($data['owner'] > 0) ? $data['owner'] : $User->id;
            }
            $AdminLTEConfig->owner = $owner;

            $AdminLTEConfig->__order = $__order;
			$AdminLTEConfig->parent_id = $parent_id;
			$AdminLTEConfig->type = $data['type'];
			$AdminLTEConfig->title = $data['title'];
            $AdminLTEConfig->default_value = $data['default_value'];
            $AdminLTEConfig->value = $data['value'];
            $AdminLTEConfig->hint = $data['hint'];
            $AdminLTEConfig->description = $data['description'];
            $AdminLTEConfig->__key = $parent_key . '.' . $data['basekey'];//$objectAdminLTE->convertTitleToConfigName($AdminLTEConfig->title);

            $metaData = [];
            $metaData['multiple'] = $data['multiple'];
            $metaData['option_titles'] = $data['option_titles'];
            $metaData['option_values'] = $data['option_values'];
            $metaData['toggle_elements'] = empty($data['toggle_elements']) ? [] : $data['toggle_elements'];
            $metaData['url'] = $data['url'];
            $metaData['content'] = $data['content'];
            $metaData['min'] = $data['min'];
            $metaData['max'] = $data['max'];
            $metaData['step'] = $data['step'];
            $metaData['file_types'] = $data['file_types'];

            $encodedData = json_encode($metaData, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
            $AdminLTEConfig->meta_data_json = $encodedData;

			$AdminLTEConfig->save();

			if (isset($data['children'])) {
                $this->saveChildren($data['children'], $AdminLTEConfig->id, $AdminLTEConfig->__key);
			}
		}
	}
}