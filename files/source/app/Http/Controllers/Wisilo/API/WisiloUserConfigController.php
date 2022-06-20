<?php

namespace App\Http\Controllers\Wisilo\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;
use App\Wisilo\WisiloUserGroup;
use App\Wisilo\WisiloModelOption;
use App\Wisilo\WisiloUserConfig;
use App\Wisilo\WisiloUserConfigVal;
use App\Wisilo\WisiloUserConfigFile;
use App\Http\Requests\Wisilo\API\WisiloUserConfigPOSTRequest;
use Illuminate\Support\Facades\DB;
use PDO;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;

class WisiloUserConfigController extends Controller
{
    public function get_recordlist(Request $request)
    {
        $parameters = $request->route()->parameters();
        $owner_group = isset($parameters['owner_group'])
            ? intval($parameters['owner_group'])
            : 0;

        $objectList = WisiloUserConfig::where('deleted', 0)->whereIn('owner_group', [0, $owner_group])->get();

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

    public function download_file(Request $request) {
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

        $strKey = $key . ':0:0';
        $__key = hash('sha256', $strKey);

        if ('default' == $type) {
            $item = WisiloUserConfigFile::where('__key', $__key)
            ->where('deleted', 0)
            ->where('file_type', 'default')
            ->first();
        } else {
            $item = WisiloUserConfigFile::where('__key', $__key)
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
        
        $objects = WisiloUserConfig::where('deleted', false)
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

        $object = WisiloUserConfig::where('deleted', 0)->where('__key', $key)->first();

        if (null !== $object) {
            $element_title = $object->title;
        }

        return $element_title;
    }

    public function get_json(Request $request) {
        $User = auth()->guard('wisilouser')->user();
        
        $parameters = $request->route()->parameters();
        $owner_group = isset($parameters['owner_group'])
            ? intval($parameters['owner_group'])
            : 0;

        $parent_data = [];
		$index = 0; 

        $WisiloUserConfigList = WisiloUserConfig::where('deleted', 0)->where('system', 1)->where('parent_id', 0)->get();
        foreach ($WisiloUserConfigList as $object) {
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
            $parent_data[$index]['show_on_group'] = isset($metaData['show_on_group']) ? $metaData['show_on_group'] : 0;
            $parent_data[$index]['show_on_user'] = isset($metaData['show_on_user']) ? $metaData['show_on_user'] : 0;
            $parent_data[$index]['show_on_profile'] = isset($metaData['show_on_profile']) ? $metaData['show_on_profile'] : 0;

            $children = $this->getChildren($object->id);

            if (0 != count($children)) {
                $parent_data[$index]['children'] = $children;
            }

            $index++;
		}

        $WisiloUserConfigList = WisiloUserConfig::where('deleted', 0)->where('system', 0)->whereIn('owner_group', [0, $owner_group])->where('parent_id', 0)->get();
		foreach ($WisiloUserConfigList as $object) {
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
            $parent_data[$index]['show_on_group'] = isset($metaData['show_on_group']) ? $metaData['show_on_group'] : 0;
            $parent_data[$index]['show_on_user'] = isset($metaData['show_on_user']) ? $metaData['show_on_user'] : 0;
            $parent_data[$index]['show_on_profile'] = isset($metaData['show_on_profile']) ? $metaData['show_on_profile'] : 0;

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

        $WisiloUserConfigList = WisiloUserConfig::where('deleted', 0)->where('parent_id', $parent_id)->get();
        $children_data = [];
		$index = 0;

		foreach ($WisiloUserConfigList as $object) {
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
            $children_data[$index]['show_on_group'] = isset($metaData['show_on_group']) ? $metaData['show_on_group'] : 0;
            $children_data[$index]['show_on_user'] = isset($metaData['show_on_user']) ? $metaData['show_on_user'] : 0;
            $children_data[$index]['show_on_profile'] = isset($metaData['show_on_profile']) ? $metaData['show_on_profile'] : 0;

            $children = $this->getChildren($object->id);;

            if (0 != count($children)) {
                $children_data[$index]['children'] = $children;
            }

            $index++;
		}

        return $children_data;
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
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $User = auth()->guard('wisilouser')->user();

        $owner_group = $request->input('owner_group');

        $objectWisilo = new Wisilo();
        $config_json = rawurldecode(htmlspecialchars_decode($request->input('config_json')));
        $config_data = json_decode($config_json,
                (JSON_HEX_QUOT
                | JSON_HEX_TAG
                | JSON_HEX_AMP
                | JSON_HEX_APOS));

		/* WisiloUserConfig::query()->truncate(); */

        WisiloUserConfig::query()->where('system', 1)->delete();
        WisiloUserConfig::query()->where('owner_group', $owner_group)->delete();

        foreach ($config_data as $__order => $data) {
			$WisiloUserConfig = new WisiloUserConfig();

            /* $__key = $objectWisilo->convertTitleToConfigName($data['title']);
            $value = $data['value'];
            if (isset($systemConfigData[$__key])) {

            } */

			$WisiloUserConfig->enabled = $data['enabled'];
            $WisiloUserConfig->system = $data['system'];
			$WisiloUserConfig->required = $data['required'];
            $WisiloUserConfig->locked = $data['locked'];

            if (1 == $data['system']) {
                $WisiloUserConfig->owner_group = 0;
                $WisiloUserConfig->owner = 0;
            } else {
                $WisiloUserConfig->owner_group = $owner_group;
                $WisiloUserConfig->owner = ($data['owner'] > 0) ? $data['owner'] : $User->id;
            }
            
			$WisiloUserConfig->__order = $__order;
			$WisiloUserConfig->parent_id = 0;
			$WisiloUserConfig->type = $data['type'];
			$WisiloUserConfig->title = $data['title'];
            $WisiloUserConfig->default_value = $data['default_value'];
            $WisiloUserConfig->value = $data['value'];
            $WisiloUserConfig->hint = $data['hint'];
            $WisiloUserConfig->description = $data['description'];
            $WisiloUserConfig->__key = $data['basekey'];//$objectWisilo->convertTitleToConfigName($WisiloUserConfig->title);

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
            $metaData['show_on_group'] = $data['show_on_group'];
            $metaData['show_on_user'] = $data['show_on_user'];
            $metaData['show_on_profile'] = $data['show_on_profile'];

            $encodedData = json_encode($metaData, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
            $WisiloUserConfig->meta_data_json = $encodedData;

			$WisiloUserConfig->save();

            if ('file' == $WisiloUserConfig->type) {
                $file_id = str_replace('.', '_', $WisiloUserConfig->__key);
                if ($request->hasFile($file_id)) {
                    $file = $request->file($file_id);
                    $this->saveDefaultFile($WisiloUserConfig, $file);
                }
            }

			if (isset($data['children'])) {
                $this->saveChildren($owner_group, $request, $data['children'], $WisiloUserConfig->id, $WisiloUserConfig->__key);
			}
		}

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
	}

    public function saveChildren($owner_group, $request, $children, $parent_id, $parent_key) {
        $User = auth()->guard('wisilouser')->user();
        $objectWisilo = new Wisilo();

		foreach ($children as $__order => $data) {
            $WisiloUserConfig = new WisiloUserConfig();

			$WisiloUserConfig->enabled = $data['enabled'];
            $WisiloUserConfig->system = $data['system'];
			$WisiloUserConfig->required = $data['required'];
            $WisiloUserConfig->locked = $data['locked'];

            if (1 == $data['system']) {
                $WisiloUserConfig->owner_group = 0;
                $WisiloUserConfig->owner = 0;
            } else {
                $WisiloUserConfig->owner_group = $owner_group;
                $WisiloUserConfig->owner = ($data['owner'] > 0) ? $data['owner'] : $User->id;
            }
            
            $WisiloUserConfig->__order = $__order;
			$WisiloUserConfig->parent_id = $parent_id;
			$WisiloUserConfig->type = $data['type'];
			$WisiloUserConfig->title = $data['title'];
            $WisiloUserConfig->default_value = $data['default_value'];
            $WisiloUserConfig->value = $data['value'];
            $WisiloUserConfig->hint = $data['hint'];
            $WisiloUserConfig->description = $data['description'];
            $WisiloUserConfig->__key = $parent_key . '.' . $data['basekey'];//$objectWisilo->convertTitleToConfigName($WisiloUserConfig->title);

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
            $metaData['show_on_group'] = $data['show_on_group'];
            $metaData['show_on_user'] = $data['show_on_user'];
            $metaData['show_on_profile'] = $data['show_on_profile'];

            $encodedData = json_encode($metaData, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS));
            $WisiloUserConfig->meta_data_json = $encodedData;

			$WisiloUserConfig->save();

            if ('file' == $WisiloUserConfig->type) {
                $file_id = str_replace('.', '_', $WisiloUserConfig->__key);
                if ($request->hasFile($file_id)) {
                    $file = $request->file($file_id);
                    $this->saveDefaultFile($WisiloUserConfig->__key, $file);
                }
            }

			if (isset($data['children'])) {
                $this->saveChildren($owner_group, $request, $data['children'], $WisiloUserConfig->id, $WisiloUserConfig->__key);
			}
		}
	}

    public function saveDefaultFile($configObject, $file) {
        $parameter = $configObject->__key;
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

        /* $processtype = $file_data['processtype'];
        if ('' == $processtype) {
            return;
        } */

        $strKey = $parameter . ':0:0';
        $__key = hash('sha256', $strKey);
       
        $object = WisiloUserConfigFile::where('deleted', 0)->where('__key', $__key)->first();

        if (null === $object) {
            $object = new WisiloUserConfigFile();
        }

        $object->__key = $__key;
        $object->file_name = $file->getClientOriginalName();
        $object->mime_type = $file->getMimeType();
        $object->file_size = $file->getSize();
        $object->file = base64_encode(file_get_contents($file->getRealPath()));
        $object->file_type = 'default';
        $object->save();

        if (null !== $configObject) {
            $configObject->default_value = $object->file_name;
            $configObject->save();
        }
    }
}