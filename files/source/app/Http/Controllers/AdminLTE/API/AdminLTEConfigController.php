<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEModelOption;
use App\AdminLTE\AdminLTEConfig;
use App\Http\Requests\AdminLTE\API\AdminLTEConfigPOSTRequest;

class AdminLTEConfigController extends Controller
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

            $objectAdminLTE = new AdminLTE();
            $displayTexts = $objectAdminLTE->getObjectDisplayTexts('AdminLTEConfig', $objectAdminLTEConfig);

            $data['id'] = $objectAdminLTEConfig->id;
            $data['id__displaytext__'] = $displayTexts['id'];
            $data['deleted'] = $objectAdminLTEConfig->deleted;
            $data['deleted__displaytext__'] = $displayTexts['deleted'];
            $data['created_at'] = $objectAdminLTEConfig->created_at;
            $data['created_at__displaytext__'] = $displayTexts['created_at'];
            $data['updated_at'] = $objectAdminLTEConfig->updated_at;
            $data['updated_at__displaytext__'] = $displayTexts['updated_at'];
                $data['enabled'] = $objectAdminLTEConfig->enabled;
            $data['enabled__displaytext__'] = $displayTexts['enabled'];
            $data['required'] = $objectAdminLTEConfig->required;
            $data['required__displaytext__'] = $displayTexts['required'];
            $data['__order'] = $objectAdminLTEConfig->__order;
            $data['__order__displaytext__'] = $displayTexts['__order'];
            $data['type'] = $objectAdminLTEConfig->type;
            $data['type__displaytext__'] = $objectAdminLTEConfig->type;//$displayTexts['type'];
            $data['parent'] = $this->getParent($objectAdminLTEConfig->__key);
            $data['parent__displaytext__'] = '';//$displayTexts['parent'];
            $data['__key'] = $objectAdminLTEConfig->__key;
            $data['__key__displaytext__'] = '';//$displayTexts['__key'];
            $data['title'] = $objectAdminLTEConfig->title;
            $data['title__displaytext__'] = $displayTexts['title'];
            $data['meta_data'] = $objectAdminLTEConfig->meta_data;
            $data['meta_data__displaytext__'] = $displayTexts['meta_data'];
        }

        return [
            'object' => $data
        ];
    }

    public function getParent($key) {
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


        $objectAdminLTEConfig->meta_data = $request->input('meta_data');

		$objectAdminLTEConfig->save();
		





        $return_data['id'] = $objectAdminLTEConfig->id;
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
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
    
        $list[$index]['id'] = 'shorttext';
        $list[$index]['text'] = 'Shorttext';
        $index++;
        
        $list[$index]['id'] = 'number';
        $list[$index]['text'] = 'Number';
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
            ->where('enabled', 1)
            ->where('type', 'group')
            ->orderBy('title', 'asc')
            ->get();

        $list = [];

        foreach ($objectList as $index => $object) {
            $list[$index]['id'] = $object->__key;
            $list[$index]['text'] = $this->getParentOptionTitle($object->__key);
        }

        return [
            'list' => $list
        ];
    }

    public function getParentOptionTitle($key) {
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
}