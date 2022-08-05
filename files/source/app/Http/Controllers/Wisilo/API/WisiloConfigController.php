<?php

namespace App\Http\Controllers\Wisilo\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloModelOption;
use App\Wisilo\WisiloConfig;
use App\Wisilo\WisiloConfigFile;
use App\Http\Requests\Wisilo\API\WisiloConfigPOSTRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use PDO;

class WisiloConfigController extends Controller
{
    public function getlist(Request $request)
    {
        $objectWisilo = new Wisilo();
        $User = auth()->guard('wisilouser')->user();
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

        $objectList = WisiloConfig::defaultQuery($search_text, $sort_variable, $sort_direction)->paginate($limit, ['*'], 'page', $page);

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
            $user_can_view = $User->can('viewAny', $object);
            if ($user_can_view && (1 == $object->enabled)) {
                $configList[$object->__key]['object'] = $object;
                $configList[$object->__key]['searched'] = false;
            }
        } // foreach ($objectList as $object)
        
        $this->setConfigTree($configList);
 
        $keys = array_keys($configList);
        
        $basekeyOrders = $this->getBasekeyOrders($configList);
        
        $keyOrders = [];

        foreach ($configList as $key => $data) {
            $object = $data['object'];

            if (!isset($keyOrders[$key])) {
                $orderedKey = '';
                $keyParts = $this->getKeyPartsForOrder($key);
                foreach ($keyParts as $part) {
                    $tempStr = str_replace($part, $basekeyOrders[$part], $part);

                    if ('' != $orderedKey) {
                        $orderedKey .= '.';
                    }

                    $orderedKey .= $tempStr;
                }

                $keyOrders[$key] = $orderedKey;
            }
        }

        natsort($keyOrders);

        $keys = array_keys($keyOrders);
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
            $list[$index]['only_admins'] = $object->only_admins;
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

            $large_screen_size = isset($metaData['large_screen_size']) ? intval($metaData['large_screen_size']) : 12;
            $medium_screen_size = isset($metaData['medium_screen_size']) ? intval($metaData['medium_screen_size']) : 12;
            $small_screen_size = isset($metaData['small_screen_size']) ? intval($metaData['small_screen_size']) : 12;
            $list[$index]['grid_class'] = 
                ' col-lg-'.$large_screen_size // desktop
                . ' col-md-'.$medium_screen_size . ' col-sm-'.$medium_screen_size // tablet
                . ' col-'.$small_screen_size; // mobile

            $list[$index]['level'] = 0;
            if (('group' == $object->type) || ('selection_group' == $object->type)) {
                $list[$index]['level'] = $this->getGroupLevel($object->__key);
            }

            $list[$index]['min_selection'] = isset($metaData['min_selection']) ? $metaData['min_selection'] : 0;
            $list[$index]['max_selection'] = isset($metaData['max_selection']) ? $metaData['max_selection'] : 0;

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

    public function getKeyPartsForOrder($key) {
        $configuredParts = [];
        $index = 0;
        $parts = explode('.', $key);
        foreach ($parts as $part) {
            if (0 == $index) {
                $configuredParts[$index] = $part;
            } else {
                $configuredParts[$index] = $configuredParts[$index-1] . '.' . $part;
            }

            $index++;
        }

        return $configuredParts;
    }

    public function getBasekeyOrders($configList) {
        $basekeyOrders = [];

        foreach ($configList as $key => $data) {
            $object = $data['object'];
            /* $basekey = $this->getBasekey($key); */

            if (!isset($basekeyOrders[$key])) {
                $basekeyOrders[$key] = $object->__order;
            }
        }

        return $basekeyOrders;
    }

    public function getGroupLevel($__key) {
        $parts = explode('.', $__key);
        $level = count($parts) - 1;
        return $level;
    }

    public function setConfigTree(&$configList) {
        $User = auth()->guard('wisilouser')->user();
        $need_search = false;
        $list = $configList;
        foreach ($list as $__key => $item) {
            $object = $item['object'];
            $parentKey = $this->getParentKey($object->__key);

            if ( ('' != $parentKey) && !isset($configList[$parentKey]) ) {
                $parentObject = $this->getConfigObject($parentKey);
                $user_can_view = $User->can('viewAny', $parentObject);
                if ($user_can_view && (null !== $parentObject) && (1 == $parentObject->enabled)) {
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

        if ($need_search) {
            $this->setConfigTree($configList);
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

        $objectList = WisiloConfig::where('deleted', 0)->get();

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

    public function getConfigObject($key) {
        return WisiloConfig::where('__key', $key)
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

    public function getBasekey($key) {
        $basekey = '';

        if ('' != $key) {
           $parts = explode('.', $key);
           $length = count($parts);
           $basekey = $parts[$length-1];
        }

        return $basekey;
    }

    public function validateSelectionGroup($selectionGroup, $val) {
        $result = [
            'has_error' => false,
            'error_msg' => ''
        ];

        $selected_options = [];
        if ('' != $val) {
            $selection_options = explode(',', $val);
        }
        $selectedCount = count($selection_options);

        $metaData = json_decode($selectionGroup->meta_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));
        $min_selection = isset($metaData['min_selection']) ? $metaData['min_selection'] : 0;
        $max_selection = isset($metaData['max_selection']) ? $metaData['max_selection'] : 0;

        if ((0 == $min_selection) && ($max_selection > 0)) {
            if ($selectedCount > $max_selection) {
                $result['has_error'] = true;
                $result['error_msg'] = "You can choose maximum " . $max_selection . " option(s).";
            }
        } else if (($min_selection > 0) && ($max_selection > 0)) {
            if (($selectedCount < $min_selection) && ($selectedCount > $max_selection)) {
                $result['has_error'] = true;
                $result['error_msg'] = "You must choose minimum " . $min_selection . " option(s). You can choose maximum " . $max_selection . " option(s).";
            } else if ($selectedCount < $min_selection) {
                $result['has_error'] = true;
                $result['error_msg'] = "You must choose minimum " . $min_selection . " option(s).";
            } else if ($selectedCount > $max_selection) {
                $result['has_error'] = true;
                $result['error_msg'] = "You can choose maximum " . $max_selection . " option(s).";
            }
        } else if (($min_selection > 0) && (0 == $max_selection)) {
            if ($selectedCount < $min_selection) {
                $result['has_error'] = true;
                $result['error_msg'] = "You must choose minimum " . $min_selection . " option(s).";
            }
        }

        return $result;
    }

    public function validation($request, $config_data) {
        $result = [
            'error_msg' => '',
            'error_count' => 0
        ];

        $errors = [];
        $index = 0;
        
        foreach ($config_data as $__order => $data) {
            $type = $data['type'];
            $key = $data['key'];
            $title = $data['title'];
            $val = isset($data['val']) ? $data['val'] : '';

            if ($data['required']) {
                if ('file' == $type) {
                    if (empty($val)) {
                        $result['error_count']++;
                        $errors[$key] = 'The <b>' . $title . '</b> field is required.';
                    } else if (!$request->hasFile($val)) {
                        $result['error_count']++;
                        $errors[$key] = 'The <b>' . $title . '</b> field is required.';
                    }
                } /* else if ('selection_group' == $type) {
                    if (empty($val)) {
                        $result['error_count']++;
                        $errors[$key] = 'The <b>' . $title . '</b> field is required.';
                    } else {
                        $selectionGroupError = $this->validateSelectionGroup($key, $val);

                        if ($selectionGroupError['has_error']) {
                            $result['error_count']++;
                            $errors[$key] = $selectionGroupError['error_msg'];
                        }
                    }
                } */ else {
                    if (empty($val)) {
                        $result['error_count']++;
                        $errors[$key] = 'The <b>' . $title . '</b> field is required.';
                    }
                }
            }

            $configObject = $this->getConfigObject($key);
            
            if (('selection_group' == $type) && ('' != $val)) {
                $selectionGroupError = $this->validateSelectionGroup($configObject, $val);

                if ($selectionGroupError['has_error']) {
                    $result['error_count']++;
                    $errors[$key] = $selectionGroupError['error_msg'];
                }
            }

            $metaData = json_decode($configObject->meta_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));
            $expression = $metaData['expression'];
            if ('' != $expression) {
                if (0 == preg_match($expression, $val)) {
                    $result['error_count']++;
                    $errors[$key] = $metaData['message'];
                }
            }

        }

        $result['error_msg'] = $errors;

        return $result;
    }

    public function post_config_data(Request $request)
    {
        $User = auth()->guard('wisilouser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $config_dataJSON = $request->input('config_data');

        $config_data = json_decode(
            $config_dataJSON,
            (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS)
        );

        $validationResult = $this->validation($request, $config_data);
        if ($validationResult['error_count'] > 0) {
            $return_data['id'] = 1;
            $return_data['has_error'] = true;
            $return_data['error_msg'] = $validationResult['error_msg'];

            return $return_data;
        }

        $files = [];
        $file_index = 0;

        foreach ($config_data as $element_data) {
            if (isset($element_data['key'])) {
                if ('file' == $element_data['type']) {
                    $files[$file_index]['parameter'] = $element_data['key'];
                    $files[$file_index]['id'] = $element_data['val'];
                    $files[$file_index]['processtype'] = $request->input($element_data['key'] . 'processtype');
                } else {
                    $this->saveConfigParameter($element_data);
                }
            }
        }

        foreach ($files as $index => $fileData) {
            if ($request->hasFile($fileData['id'])) {
                $file = $request->file($fileData['id']);
                $this->saveFile($fileData['parameter'], $file);
            } else {
                $this->updateFile($fileData);
            }
        }

        $return_data['id'] = 1;
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

    public function updateFile($file_data) {
        $processtype = $file_data['processtype'];
        if ('' == $processtype) {
            return;
        }

        if (('set_default' == $processtype) || ('removed' == $processtype)) {
            WisiloConfigFile::where('file_type', 'uploaded')->where('parameter', $file_data['parameter'])->delete();

            $configObject = $this->getConfigObject($file_data['parameter']);
            if (null !== $configObject) {
                $configObject->value = '';
                $configObject->save();
            }
        }
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
       
        $object = WisiloConfigFile::where('deleted', 0)->where('file_type', '!=', 'default')->where('parameter', $parameter)->first();

        if (null === $object) {
            $object = new WisiloConfigFile();
        }

        $object->parameter = $parameter;
        $object->file_name = $file->getClientOriginalName();
        $object->mime_type = $file->getMimeType();
        $object->file_size = $file->getSize();
        $object->file = base64_encode(file_get_contents($file->getRealPath()));
        $object->file_type = 'uploaded';
        $object->save();

        $configObject = $this->getConfigObject($parameter);
        if (null !== $configObject) {
            $configObject->value = $object->file_name;
            $configObject->save();
        }
    }

    public function download(Request $request) {
        $parameters = $request->route()->parameters();

        $type = isset($parameters['type'])
            ? htmlspecialchars($parameters['type'])
            : '';

        $key = isset($parameters['key'])
            ? htmlspecialchars($parameters['key'])
            : '';

        if (('' == $type) ||('' == $key)) {
            header('HTTP/1.0 404 Not Found');
            header('Status: 404 Not Found');
            die();
        } // if (0 == $id) {

        if ('default' == $type) {
            $item = WisiloConfigFile::where('parameter', $key)
            ->where('deleted', 0)
            ->where('file_type', 'default')
            ->first();
        } else {
            $item = WisiloConfigFile::where('parameter', $key)
            ->where('deleted', 0)
            ->where('file_type', 'uploaded')
            ->first();
        }

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
        $User = auth()->guard('wisilouser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];

        $selected_ids = $request->input('selected_ids');
        
        $objects = WisiloConfig::where('deleted', false)
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

    public function get_parentlist(Request $request) {
        $parameters = $request->route()->parameters();

        $id = isset($parameters['id'])
            ? intval($parameters['id'])
            : 0;

        $objectList = WisiloConfig::where('id', '!=', $id)
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

        $objectList = WisiloConfig::where('id', '!=', $id)
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

        $object = WisiloConfig::where('deleted', 0)->where('__key', $key)->first();

        if (null !== $object) {
            $element_title = $object->title;
        }

        return $element_title;
    }

    public function get_json() {
        $User = auth()->guard('wisilouser')->user();

        $WisiloConfigList = WisiloConfig::where('deleted', 0)->where('parent_id', 0)->get();
		$parent_data = [];
		$index = 0;        

		foreach ($WisiloConfigList as $object) {
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
            $parent_data[$index]['only_admins'] = (1 == $object->only_admins);
            $parent_data[$index]['required'] = (1 == $object->required);
            $parent_data[$index]['__key'] = $object->__key;
            $parent_data[$index]['basekey'] = $this->getBasekey($object->__key);
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
            $parent_data[$index]['large_screen_size'] = isset($metaData['large_screen_size']) ? intval($metaData['large_screen_size']) : 12;
            $parent_data[$index]['medium_screen_size'] = isset($metaData['medium_screen_size']) ? intval($metaData['medium_screen_size']) : 12;
            $parent_data[$index]['small_screen_size'] = isset($metaData['small_screen_size']) ? intval($metaData['small_screen_size']) : 12;
            $parent_data[$index]['min_selection'] = isset($metaData['min_selection']) ? $metaData['min_selection'] : 0;
            $parent_data[$index]['max_selection'] = isset($metaData['max_selection']) ? $metaData['max_selection'] : 0;
            $parent_data[$index]['expression'] = isset($metaData['expression']) ? $metaData['expression'] : '';
            $parent_data[$index]['message'] = isset($metaData['message']) ? $metaData['message'] : '';

            $children = $this->getChildren($object->id);

            if (0 != count($children)) {
                $parent_data[$index]['children'] = $children;
            }

            $index++;
		}
        
        return $parent_data;
	}

    public function getChildren($parent_id) {
        $User = auth()->guard('wisilouser')->user();

        $WisiloConfigList = WisiloConfig::where('deleted', 0)->where('parent_id', $parent_id)->get();
        $children_data = [];
		$index = 0;

		foreach ($WisiloConfigList as $object) {
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
            $children_data[$index]['only_admins'] = (1 == $object->only_admins);
            $children_data[$index]['required'] = (1 == $object->required);
            $children_data[$index]['__key'] = $object->__key;
            $children_data[$index]['basekey'] = $this->getBasekey($object->__key);
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
            $children_data[$index]['large_screen_size'] = isset($metaData['large_screen_size']) ? intval($metaData['large_screen_size']) : 12;
            $children_data[$index]['medium_screen_size'] = isset($metaData['medium_screen_size']) ? intval($metaData['medium_screen_size']) : 12;
            $children_data[$index]['small_screen_size'] = isset($metaData['small_screen_size']) ? intval($metaData['small_screen_size']) : 12;
            $children_data[$index]['min_selection'] = isset($metaData['min_selection']) ? $metaData['min_selection'] : 0;
            $children_data[$index]['max_selection'] = isset($metaData['max_selection']) ? $metaData['max_selection'] : 0;
            $children_data[$index]['expression'] = isset($metaData['expression']) ? $metaData['expression'] : '';
            $children_data[$index]['message'] = isset($metaData['message']) ? $metaData['message'] : '';

            $children = $this->getChildren($object->id);;

            if (0 != count($children)) {
                $children_data[$index]['children'] = $children;
            }

            $index++;
		}

        return $children_data;
    }

    public function getSystemConfigData() {
        $WisiloConfigList = WisiloConfig::where('deleted', 0)->where('system', 1)->get();
		$systemConfigData = [];

		foreach ($WisiloConfigList as $object) {
            $systemConfigData[$object->__key] = $object->value;
		}
        
        return $systemConfigData;
    }

    public function saveDefaultFile($parameter, $file) {
        /* //File Name
        echo $file->getClientOriginalName() . '<br>';
    
        //Display File Extension
        echo $file->getClientOriginalExtension() . '<br>';

        //Display File Real Path
        echo $file->getRealPath() . '<br>';

        //Display File Size
        echo $file->getSize() . '<br>';

        //Display File Mime Type
        echo $file->getMimeType() . '<br>';

        die(); */
       
        $object = WisiloConfigFile::where('deleted', 0)->where('parameter', $parameter)->first();

        if (null === $object) {
            $object = new WisiloConfigFile();
        }

        $object->parameter = $parameter;
        $object->file_name = $file->getClientOriginalName();
        $object->mime_type = $file->getMimeType();
        $object->file_size = $file->getSize();
        $object->file = base64_encode(file_get_contents($file->getRealPath()));
        $object->file_type = 'default';
        $object->save();

        $configObject = $this->getConfigObject($parameter);
        if (null !== $configObject) {
            $configObject->default_value = $object->file_name;
            $configObject->save();
        }
    }

    public function validate_item(Request $request) {
        $return_data = [];
        $return_data['has_error'] = false;
        $return_data['errors'] = [];

        $expression = $request->input('expression');

        if (@preg_match($expression, '') === false) {
            $return_data['has_error'] = true;
            $return_data['errors']['expression'] = __('Invalid expression.');

            return $return_data;
        }

        return $return_data;
    }

	public function post_json(Request $request) {
        /* $systemConfigData = $this->getSystemConfigData(); */

        $User = auth()->guard('wisilouser')->user();

        $objectWisilo = new Wisilo();
        $config_json = rawurldecode(htmlspecialchars_decode($request->input('config_json')));
        $config_data = json_decode($config_json,
                (JSON_HEX_QUOT
                | JSON_HEX_TAG
                | JSON_HEX_AMP
                | JSON_HEX_APOS));

		WisiloConfig::query()->truncate();

        foreach ($config_data as $__order => $data) {
			$WisiloConfig = new WisiloConfig();

            /* $__key = $objectWisilo->convertTitleToConfigName($data['title']);
            $value = $data['value'];
            if (isset($systemConfigData[$__key])) {

            } */

			$WisiloConfig->enabled = $data['enabled'];
            $WisiloConfig->only_admins = $data['only_admins'];
            $WisiloConfig->system = $data['system'];
			$WisiloConfig->required = $data['required'];
            $WisiloConfig->locked = $data['locked'];

            $owner = 0;
            if (0 == $data['system']) {
                $owner = ($data['owner'] > 0) ? $data['owner'] : $User->id;
            }
            $WisiloConfig->owner = $owner;
            
			$WisiloConfig->__order = $__order;
			$WisiloConfig->parent_id = 0;
			$WisiloConfig->type = $data['type'];
			$WisiloConfig->title = $data['title'];
            $WisiloConfig->default_value = $data['default_value'];
            $WisiloConfig->value = $data['value'];
            $WisiloConfig->hint = $data['hint'];
            $WisiloConfig->description = $data['description'];
            $WisiloConfig->__key = $data['basekey'];//$objectWisilo->convertTitleToConfigName($WisiloConfig->title);

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
            $metaData['large_screen_size'] = $data['large_screen_size'];
            $metaData['medium_screen_size'] = $data['medium_screen_size'];
            $metaData['small_screen_size'] = $data['small_screen_size'];
            $metaData['min_selection'] = $data['min_selection'];
            $metaData['max_selection'] = $data['max_selection'];
            $metaData['expression'] = $data['expression'];
            $metaData['message'] = $data['message'];

            $encodedData = json_encode($metaData, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
            $WisiloConfig->meta_data_json = $encodedData;

			$WisiloConfig->save();

            if ('file' == $WisiloConfig->type) {
                $convertedKey = str_replace('.', '_', $WisiloConfig->__key);

                if ($request->hasFile($convertedKey)) {
                    $file = $request->file($convertedKey);
                    $this->saveDefaultFile($WisiloConfig->__key, $file);
                }
            }

			if (isset($data['children'])) {
                $this->saveChildren($request, $data['children'], $WisiloConfig->id, $WisiloConfig->__key);
			}
		}
	}

    public function saveChildren($request, $children, $parent_id, $parent_key) {
        $User = auth()->guard('wisilouser')->user();
        $objectWisilo = new Wisilo();

		foreach ($children as $__order => $data) {
            $WisiloConfig = new WisiloConfig();

			$WisiloConfig->enabled = $data['enabled'];
            $WisiloConfig->only_admins = $data['only_admins'];
            $WisiloConfig->system = $data['system'];
			$WisiloConfig->required = $data['required'];
            $WisiloConfig->locked = $data['locked'];

            $owner = 0;
            if (0 == $data['system']) {
                $owner = ($data['owner'] > 0) ? $data['owner'] : $User->id;
            }
            $WisiloConfig->owner = $owner;

            $WisiloConfig->__order = $__order;
			$WisiloConfig->parent_id = $parent_id;
			$WisiloConfig->type = $data['type'];
			$WisiloConfig->title = $data['title'];
            $WisiloConfig->default_value = $data['default_value'];
            $WisiloConfig->value = $data['value'];
            $WisiloConfig->hint = $data['hint'];
            $WisiloConfig->description = $data['description'];
            $WisiloConfig->__key = $parent_key . '.' . $data['basekey'];//$objectWisilo->convertTitleToConfigName($WisiloConfig->title);

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
            $metaData['large_screen_size'] = $data['large_screen_size'];
            $metaData['medium_screen_size'] = $data['medium_screen_size'];
            $metaData['small_screen_size'] = $data['small_screen_size'];
            $metaData['min_selection'] = $data['min_selection'];
            $metaData['max_selection'] = $data['max_selection'];
            $metaData['expression'] = $data['expression'];
            $metaData['message'] = $data['message'];

            $encodedData = json_encode($metaData, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
            $WisiloConfig->meta_data_json = $encodedData;

			$WisiloConfig->save();

            if ('file' == $WisiloConfig->type) {
                $convertedKey = str_replace('.', '_', $WisiloConfig->__key);

                if ($request->hasFile($convertedKey)) {
                    $file = $request->file($convertedKey);
                    $this->saveDefaultFile($WisiloConfig->__key, $file);
                }
            }

			if (isset($data['children'])) {
                $this->saveChildren($request, $data['children'], $WisiloConfig->id, $WisiloConfig->__key);
			}
		}
	}
}