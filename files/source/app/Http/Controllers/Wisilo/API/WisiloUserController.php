<?php

namespace App\Http\Controllers\Wisilo\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloModelOption;
use App\Wisilo\WisiloUserGroup;
use App\Wisilo\WisiloUser;
use App\Wisilo\WisiloUserConfig;
use App\Wisilo\WisiloUserConfigVal;
use App\Wisilo\WisiloUserConfigFile;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use App\Http\Requests\Wisilo\API\WisiloUserPOSTRequest;

class WisiloUserController extends Controller
{
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
        } // if ('new' == htmlspecialchars($parameters['id'])) {

        if ($id > 0) {
            $objectWisiloUser = WisiloUser::where('id', $id)->where('deleted', 0)->first();
        }
        
        $User = auth()->guard('wisilouser')->user();

        if ($User->can('viewAny', WisiloUser::class) && $User->can('view', $objectWisiloUser)) {
            $data['user_can_create'] = $User->can('create', WisiloUser::class);
            $data['user_can_read'] = $User->can('view', $objectWisiloUser);
            $data['user_can_update'] = $User->can('update', $objectWisiloUser);
            $data['user_can_delete'] = $User->can('delete', $objectWisiloUser);
            $data['user_can_view'] = $User->can('viewAny', $objectWisiloUser);

            $objectWisilo = new Wisilo();

            $displayTexts = $objectWisilo->getObjectDisplayTexts('WisiloUser', $objectWisiloUser);

            $data['id'] = $objectWisiloUser->id;
            $data['id__displaytext__'] = $displayTexts['id'];
            $data['deleted'] = $objectWisiloUser->deleted;
            $data['deleted__displaytext__'] = $displayTexts['deleted'];
            $data['created_at'] = $objectWisiloUser->created_at;
            $data['created_at__displaytext__'] = $displayTexts['created_at'];
            $data['updated_at'] = $objectWisiloUser->updated_at;
            $data['updated_at__displaytext__'] = $displayTexts['updated_at'];
            $data['enabled'] = $objectWisiloUser->enabled;
            $data['enabled__displaytext__'] = $displayTexts['enabled'];
            $data['wisilousergroup_id'] = array($objectWisiloUser->wisilousergroup_id);
            $data['wisilousergroup_id__displaytext__'] = $displayTexts['wisilousergroup_id'];
            $data['fullname'] = $objectWisiloUser->fullname;
            $data['fullname__displaytext__'] = $displayTexts['fullname'];
            $data['username'] = $objectWisiloUser->username;
            $data['username__displaytext__'] = $displayTexts['username'];
            $data['email'] = $objectWisiloUser->email;
            $data['email__displaytext__'] = $displayTexts['email'];
            $data['password'] = '';
            $data['password__displaytext__'] = '******';

            $external_ids = array();
            foreach ($objectWisilo->get_model_files_by_property('WisiloUser', $objectWisiloUser->id, 'profile_img') as $fileData) {
                $external_ids[] = $fileData['id'];
            }

            if(empty($external_ids)){
                $current_external_value = '';
            } else {
                $current_external_value = implode(',', $external_ids);
            }

            $data['profile_img'] = $current_external_value;
            $data['profile_img__displaytext__'] = $displayTexts['profile_img'];
        } // if (null !== $objectWisiloUser) {

        return [
            'object' => $data,
            'has_config_parameter' => $this->groupHasConfigParameter($objectWisiloUser->wisilousergroup_id)
        ];
    }

    public function post(WisiloUserPOSTRequest $request)
    {
        $User = auth()->guard('wisilouser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        $bNewUser = false;
        
        $id = intval($request->input('id'));

        if ($id > 0) {
            $objectWisiloUser = WisiloUser::find($id);
            if (!$User->can('update', $objectWisiloUser)) {
                $has_error = true;
                $error_msg = __('You can not update this object. Contact your system administrator for more information.');
            }
        } else {
            $bNewUser = true;

            $objectWisiloUser = new WisiloUser();
            if (!$User->can('create', WisiloUser::class)) {
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
        
        $objectWisiloUser->deleted = 0;
        if ($id > 0) {
            $objectWisiloUser->updated_by = $User->id;
        } else {
            $objectWisiloUser->created_by = $User->id;
            $objectWisiloUser->updated_by = $User->id;
        } // if ($id > 0) {

        $objectWisiloUser->enabled = ('' != $request->input('enabled'))
            ? intval($request->input('enabled'))
            : 0;

        if ('' != $request->input('wisilousergroup_id')) {
            $objectWisiloUser->wisilousergroup_id = intval($request->input('wisilousergroup_id'));
        }

        $objectWisiloUser->fullname = $request->input('fullname');
        
        $objectWisiloUser->username = $request->input('username');

        $objectWisiloUser->email = $request->input('email');
        
        if ('' != $request->input('password')) {
            $objectWisiloUser->password = bcrypt($request->input('password'));
        } 

        $profile_img = $request->input('profile_img');

        $objectWisiloUser->save();
        
        $objectWisilo = new Wisilo();
        $objectWisilo->updateModelFileObject('WisiloUser', $objectWisiloUser->id, 'profile_img', $profile_img);
 
        $return_data['id'] = $objectWisiloUser->id;
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

    public function delete(Request $request)
    {
        $User = auth()->guard('wisilouser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $selected_ids = $request->input('selected_ids');
        
        $objects = WisiloUser::where('deleted', false)
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
                $object->updated_by = $User->id;
                $object->save();                
            } // foreach ($objects as $object)
        }
            
        $return_data['has_error'] = true;
        $return_data['error_msg'] = $error_msg;

        return $return_data;
    }

    public function get_files(Request $request) {
        $list = [];
        
        $parameters = $request->route()->parameters();
        
        if (!isset($parameters['id'])) {
            return;
        } // if (!isset($parameters['id'])) {

        $object_id = intval($parameters['id']);

        if (0 == $object_id) {
            return;
        } // if (!isset($parameters['id'])) {
        
        $objectWisilo = new Wisilo();
        $files = $objectWisilo->get_model_files('WisiloUser', $object_id);
        $index = 0;

        foreach ($files as $fileData) {
            $list[$index]["id"] = $fileData["id"];
            $list[$index]["object_property"] = $fileData["object_property"];
            $list[$index]["file_name"] = $fileData["file_name"];
            $list[$index]["path"] = $fileData["path"];
            $list[$index]["media_type"] = $fileData["media_type"];

            $fileNameTokens = explode('.', $fileData["file_name"]);
            $list[$index]["extension"] = strtolower(end($fileNameTokens));

            $index++;
        }

        return [
            'list' => $list
        ];
    }

    public function groupHasConfigParameter($id) {
        $configList = WisiloUserConfig::whereIn('owner_group', [0, $id])
            ->where('deleted', 0)
            ->where('enabled', 1)
            ->get();

        if (0 == count($configList)) {
            return false;
        }

        foreach ($configList as $object) {
            $metaData = json_decode($object->meta_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));
            $show_on_group = isset($metaData['show_on_group']) ? intval($metaData['show_on_group']) : 0;
            $show_on_user = isset($metaData['show_on_user']) ? intval($metaData['show_on_user']) : 0;
            $show_on_profile = isset($metaData['show_on_profile']) ? intval($metaData['show_on_profile']) : 0;

            if (1 == $show_on_user) {
                return true;
            }
        }

        return false;
    }

    public function get_config_data(Request $request)
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

        $objectId = 0;
        if ($o = \Request::get('o')) {
            $objectId = $o;
        }

        $objectWisiloUser = WisiloUser::find($objectId);
        $owner_group = $objectWisiloUser->wisilousergroup_id;

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

        $objectList = WisiloUserConfig::defaultQuery($search_text, $sort_variable, $sort_direction)->paginate($limit, ['*'], 'page', $page);

        $current_page = $objectList->currentPage();
        $last_page = $objectList->lastPage();
        $per_page = $objectList->perPage();
        $from = (($current_page - 1) * $per_page) + 1;
        $to = ($current_page * $per_page);
        $total = $objectList->total();
        $next_page_url = ($last_page == $current_page) ? null : 'get_config_data?p=' . ($current_page + 1);
        $prev_page_url = (1 == $current_page) ? null : 'get_config_data?p=' . ($current_page - 1);

        $configList = [];

        foreach ($objectList as $object) {
            $metaData = json_decode($object->meta_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));
            $show_on_group = isset($metaData['show_on_group']) ? intval($metaData['show_on_group']) : 0;
            $show_on_user = isset($metaData['show_on_user']) ? intval($metaData['show_on_user']) : 0;
            $show_on_profile = isset($metaData['show_on_profile']) ? intval($metaData['show_on_profile']) : 0;

            if ((1 == $object->enabled) && (($owner_group == $object->owner_group) || (1 == $object->system))) {
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

            $metaData = json_decode($object->meta_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));
            $show_on_group = isset($metaData['show_on_group']) ? intval($metaData['show_on_group']) : 0;
            $show_on_user = isset($metaData['show_on_user']) ? intval($metaData['show_on_user']) : 0;
            $show_on_profile = isset($metaData['show_on_profile']) ? intval($metaData['show_on_profile']) : 0;

            if (1 == $show_on_user) {
                $list[$index] = array();
                $list[$index]['id'] = $object->id;
                $list[$index]['deleted'] = $object->deleted;
                $list[$index]['created_at'] = $object->created_at;
                $list[$index]['updated_at'] = $object->updated_at;
                $list[$index]['owner_group'] = $object->owner_group;
                $list[$index]['enabled'] = $object->enabled;
                $list[$index]['required'] = $object->required;
                $list[$index]['__order'] = $object->__order;
                $list[$index]['type'] = $object->type;
                $list[$index]['parent'] = $this->getParentKey($object->__key);
                $list[$index]['__key'] = $object->__key;
                $list[$index]['title'] = $object->title;
                $list[$index]['default_value'] = $object->default_value;
                $list[$index]['description'] = $object->description;
                $list[$index]['hint'] = $object->hint;
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
                $list[$index]['show_on_group'] = $show_on_group;
                $list[$index]['show_on_user'] = $show_on_user;
                $list[$index]['show_on_profile'] = $show_on_profile;

                $list[$index]['value'] = '';
                if ('group' != $object->type) {
                    if ('file' == $object->type) {
                        $list[$index]['value'] = $this->getConfigFileVal($object->__key, $objectId, $show_on_group, $owner_group);
                    } else {
                        $list[$index]['value'] = $this->getConfigVal($object->__key, $objectId, $show_on_group, $owner_group);
                    }
                }

                $index++;
            }
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

    public function setConfigTree(&$configList) {
        $need_search = false;
        $list = $configList;
        foreach ($list as $__key => $item) {
            $object = $item['object'];
            //echo 'objectKey:' . $object->__key . '<br>';
            $parentKey = $this->getParentKey($object->__key);
            //echo 'parentKey:' . $parentKey . '<br>';

             if ( ('' != $parentKey) && !isset($configList[$parentKey]) ) {
                $parentObject = $this->getConfigObjectById($object->parent_id);
                
                $metaData = json_decode($parentObject->meta_data_json, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP| JSON_HEX_APOS));
                $show_on_group = isset($metaData['show_on_group']) ? intval($metaData['show_on_group']) : 0;
                $show_on_user = isset($metaData['show_on_user']) ? intval($metaData['show_on_user']) : 0;
                $show_on_profile = isset($metaData['show_on_profile']) ? intval($metaData['show_on_profile']) : 0;

                if (1 == $show_on_user) {
                    if ((null !== $parentObject) && (1 == $parentObject->enabled)) {
                        $configList[$parentKey]['object'] = $parentObject;
                        $configList[$parentKey]['searched'] = false;
                        $need_search = true;
                        $configList[$__key]['searched'] = true;
                    } else {
                        // clear unabled sub elements
                        $this->cleanUnabledSubelements($configList, $parentKey);
                    }
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

    public function getConfigObjectById($id) {
        return WisiloUserConfig::where('id', $id)
            /* ->where('owner_group', $owner_group) */
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

    public function getConfigVal($configKey, $objectId, $show_on_group, $owner_group) {
        $val = '';
        $strKey = $configKey . ':0:' . $objectId;
        $__key = hash('sha256', $strKey);

        $object = WisiloUserConfigVal::where('__key', $__key)
            ->where('deleted', 0)
            ->first();

        if (null !== $object) {
            try {
                $val = Crypt::decryptString($object->value);
            } catch (DecryptException $e) {
                $val = '';
            }
        }

        if (('' == $val) && (1 == $show_on_group)) {
            $val = $this->getGroupConfigVal($configKey, $owner_group);
        }

        return $val;
    }

    public function getGroupConfigVal($configKey, $objectId) {
        $val = '';
        $strKey = $configKey . ':' . $objectId . ':' . '0';
        $__key = hash('sha256', $strKey);

        $object = WisiloUserConfigVal::where('__key', $__key)
            ->where('deleted', 0)
            ->first();

        if (null !== $object) {
            try {
                $val = Crypt::decryptString($object->value);
            } catch (DecryptException $e) {
                $val = '';
            }
        }

        return $val;
    }

    public function getConfigFileVal($configKey, $objectId, $show_on_group, $owner_group) {
        $val = '';
        $strKey = $configKey . ':0:' . $objectId;
        $__key = hash('sha256', $strKey);

        $object = WisiloUserConfigFile::where('__key', $__key)
            ->where('deleted', 0)
            ->first();

        if (null !== $object) {
            $val = $object->file_name;
        }

        if (('' == $val) && (1 == $show_on_group)) {
            $val = $this->getGroupConfigVal($configKey, $owner_group);
        }

        return $val;
    }

    public function getGroupConfigFileVal($configKey, $objectId) {
        $val = '';
        $strKey = $configKey . ':' . $objectId . ':' . '0';
        $__key = hash('sha256', $strKey);

        $object = WisiloUserConfigFile::where('__key', $__key)
            ->where('deleted', 0)
            ->first();

        if (null !== $object) {
            $val = $object->file_name;
        }

        return $val;
    }

    public function download_file(Request $request) {
        $parameters = $request->route()->parameters();

        $type = isset($parameters['type'])
            ? htmlspecialchars($parameters['type'])
            : '';

        $key = isset($parameters['key'])
            ? htmlspecialchars($parameters['key'])
            : '';

        $current_id = isset($parameters['current_id'])
            ? intval($parameters['current_id'])
            : 0;

        $strKey = $key . ':0:' . $current_id;
        $__key = hash('sha256', $strKey);

        if (('' == $type) ||('' == $key)) {
            header('HTTP/1.0 404 Not Found');
            header('Status: 404 Not Found');
            die();
        } // if (0 == $id) {

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

            if (is_null($item)) {
                $objectWisiloUser = WisiloUser::find($current_id);
                $owner_group = $objectWisiloUser->wisilousergroup_id;

                $strKey = $key . ':' . $owner_group . ':0';
                $__key = hash('sha256', $strKey);

                $item = WisiloUserConfigFile::where('__key', $__key)
                    ->where('deleted', 0)
                    ->where('file_type', 'uploaded')
                    ->first();
            }
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
            $object_id = $data['object_id'];
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
                } else {
                    if (empty($val)) {
                        $result['error_count']++;
                        $errors[$key] = 'The <b>' . $title . '</b> field is required.';
                    }
                }
            }
            
            $configObject = $this->getConfigObjectById($object_id);

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
        
        $objectId = $request->input('objectId');

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
                $this->saveConfigParameter($element_data, $objectId);

                if ('file' == $element_data['type']) {
                    $files[$file_index]['parameter'] = $element_data['key'];
                    $files[$file_index]['id'] = $element_data['val'];
                    $files[$file_index]['processtype'] = $request->input($element_data['key'] . 'processtype');
                }
            }
        }

        foreach ($files as $index => $fileData) {
            if ($request->hasFile($fileData['id'])) {
                $file = $request->file($fileData['id']);
                $this->saveFile($objectId, $fileData['parameter'], $file);
            } else {
                $this->updateFile($objectId, $fileData);
            }
        }

        $return_data['id'] = 1;
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

    public function saveConfigParameter($element_data, $objectId)
    {
        $val = '';
        if (isset($element_data['val'])) {
            $val = $element_data['val'];
        }

        $strKey = $element_data['key'] . ':0:' . $objectId;
        $__key = hash('sha256', $strKey);

        $object = WisiloUserConfigVal::where('__key', $__key)
            ->where('deleted', 0)
            ->first();

        if (null !== $object) {
            $object->value = Crypt::encryptString($val);
            $object->save();
        } else {
            $object = new WisiloUserConfigVal();
            $object->__key = $__key;
            $object->value = Crypt::encryptString($val);
            $object->save();
        }
    }

    public function saveFile($objectId, $parameter, $file) {
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

        $file_name = $file->getClientOriginalName();

        $strKey = $parameter . ':0:' . $objectId;
        $__key = hash('sha256', $strKey);

        $object = WisiloUserConfigFile::where('deleted', 0)->where('file_type', '!=', 'default')->where('__key', $__key)->first();
        if (null === $object) {
            $object = new WisiloUserConfigFile();
        }

        $object->__key = $__key;
        $object->file_name = $file->getClientOriginalName();
        $object->mime_type = $file->getMimeType();
        $object->file_size = $file->getSize();
        $object->file = base64_encode(file_get_contents($file->getRealPath()));
        $object->file_type = 'uploaded';
        $object->save();


        $objConfigVal = WisiloUserConfigVal::where('__key', $__key)
            ->where('deleted', 0)
            ->first();

        $objConfigVal->__key = $__key;
        $objConfigVal->value = Crypt::encryptString($file_name);
        $objConfigVal->save();
    }

    public function updateFile($objectId, $file_data) {
        $processtype = $file_data['processtype'];
        if ('' == $processtype) {
            return;
        }

        $strKey = $element_data['key'] . ':0:' . $objectId;
        $__key = hash('sha256', $strKey);

        $objConfigVal = WisiloUserConfigVal::where('__key', $__key)
            ->where('deleted', 0)
            ->first();

        if (('set_default' == $processtype) || ('removed' == $processtype)) {
            WisiloUserConfigFile::where('file_type', 'uploaded')->where('__key', $__key)->delete();

            if (null !== $objConfigVal) {
                $objConfigVal->value = '';
                $objConfigVal->save();
            }
        }
    }
}