<?php

namespace App\Http\Controllers\AdminLTE\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\AdminLTE\AdminLTEUserGroup;
use App\AdminLTE\AdminLTECustomVariable;
use App\AdminLTE\AdminLTEConfig;
use App\AdminLTE\AdminLTEUserConfig;

class AdminLTEController extends Controller
{

    public function get_page_variables(Request $request)
    {   
        $parameters = $request->route()->parameters();

        $componentName = isset($parameters['componentName'])
                ? htmlspecialchars($parameters['componentName'])
                : '';

        $objectAdminLTE = new AdminLTE();

        // widget edit button
        $show_widget_config_button = false;
        if (Gate::allows('editWidget')) {
            $show_widget_config_button = true;        
        }

        // is user admin ?
        $admin = false;
        if (Gate::allows('isAdmin')) {
            $admin = true;
        }

        // widgets
        $active_widgets = $objectAdminLTE->getUserWidgets($componentName);
        
        // Permissions
        $menu_permissions = $objectAdminLTE->getUserMenuPermissions();
        $model_permissions = $objectAdminLTE->getUserModelPermissions();

        return [
            'is_admin' => $admin,
            'show_widget_config_button' => $show_widget_config_button,
            'menu_permissions' => $menu_permissions,
            'model_permissions' => $model_permissions,
            'active_widgets' => $active_widgets
        ];
    }

    public function get_custom_variables(Request $request) {
        $parameters = $request->route()->parameters();
        $currentUser = auth()->guard('adminlteuser')->user();

        $objectList = AdminLTECustomVariable::where('deleted', 0)
            ->where('adminlteusergroup_id', $currentUser->adminlteusergroup_id)
            ->get();

        $list = [];
        $index = 0;

        foreach ($objectList as $object) {
            $list[$index]['id'] = $object->id;
            $list[$index]['group'] = $object->group;
            $list[$index]['adminlteusergroup_id'] = $object->adminlteusergroup_id;
            $list[$index]['title'] = $object->title;
            $list[$index]['name'] = $object->name;
            $list[$index]['value'] = $object->value;

            $index++;
        }

        return [
            'list' => $list
        ];
    }

    public function post_custom_variable(Request $request)
    {
        $currentUser = auth()->guard('adminlteuser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $id = intval($request->input('id'));

        if ($id > 0) {
            $objectAdminLTECustomVariable = AdminLTECustomVariable::find($id);
        } else {
            $objectAdminLTECustomVariable = new AdminLTECustomVariable();
        } // if ($id > 0) {

        $title = $request->input('title');
        $name = $request->input('name');
        $value = $request->input('value');
        
        $object = AdminLTECustomVariable::where('deleted', 0)
            ->where('adminlteusergroup_id', $currentUser->adminlteusergroup_id)
            ->where('name', $name)
            ->where('id', '!=', $id)
            ->first();

        if (null !== $object) {
            $has_error = true;
            $error_msg = __('This variable name is in use. Please enter another variable name.');
        }

        if ($has_error) {
            $return_data['id'] = $id;
            $return_data['has_error'] = $has_error;
            $return_data['error_msg'] = $error_msg;

            return $return_data;
        }
        
        $objectAdminLTECustomVariable->deleted = 0;
        if ($id > 0) {
            $objectAdminLTECustomVariable->updated_by = $currentUser->id;
        } else {
            $objectAdminLTECustomVariable->created_by = $currentUser->id;
            $objectAdminLTECustomVariable->updated_by = $currentUser->id;
        } // if ($id > 0) {

        $objectAdminLTECustomVariable->adminlteusergroup_id = $currentUser->adminlteusergroup_id;         
        $objectAdminLTECustomVariable->title = $title;
        $objectAdminLTECustomVariable->name = $name;
        $objectAdminLTECustomVariable->value = $value;
        $objectAdminLTECustomVariable->__order = 0;
        $objectAdminLTECustomVariable->save();
        
        $return_data['id'] = $objectAdminLTECustomVariable->id;
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

    public function delete_custom_variable(Request $request)
    {
        $User = auth()->guard('adminlteuser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $id = $request->input('id');
        
        $object = AdminLTECustomVariable::where('deleted', false)
                ->where('id', $id)
                ->first();

        if (null !== $object) {
            $object->deleted = 1;
            $object->updated_by = $User->id;
            $object->save();                
        } // if (null !== $object) {
            
        return $return_data;
    } 

    public function get_global_parameter_options(Request $request)
    {
        $parameters = $request->route()->parameters();

        $objectList = AdminLTEConfig::where('deleted', 0)->get();

        $list = [];
        $index = 0;

        foreach ($objectList as $object) {
            if (('group' == $object->type) || ('selection_group' == $object->type)) {
                continue;
            }

            $list[$index]['id'] = 'GlobalParameters/' . $object->__key;
            $list[$index]['text'] = $this->getGlobalParameterOptionTitle($object->__key);
            $index++;
        }

        return [
            'list' => $list
        ];
    }

    public function getGlobalParameterOptionTitle($key) {
        $option_title = '';
        $parts = explode('.', $key);
        $parent_key = '';

        foreach ($parts as $part) {
            if ('' != $parent_key) {
                $parent_key .= '.';
            }

            $parent_key .= $part;

            $title = $this->getGlobalParameterTitle($parent_key);

            if ('' != $option_title) {
                $option_title .= ' / ';
            }

            $option_title .= $title;
        }

        return $option_title;
    }

    public function getGlobalParameterTitle($key) {
        $element_title = '';

        $object = AdminLTEConfig::where('deleted', 0)->where('__key', $key)->first();

        if (null !== $object) {
            $element_title = $object->title;
        }

        return $element_title;
    }

    public function get_user_parameter_options(Request $request)
    {
        $parameters = $request->route()->parameters();
        $currentUser = auth()->guard('adminlteuser')->user();
        $currentGroupId = $currentUser->adminlteusergroup_id;

        $objectList = AdminLTEUserConfig::where('deleted', 0)->whereIn('owner_group', [0, $currentGroupId])->get();

        $list = [];
        $index = 0;

        foreach ($objectList as $object) {
            if (('group' == $object->type) || ('selection_group' == $object->type)) {
                continue;
            }

            $list[$index]['id'] = 'UserParameters/' . $object->__key;
            $list[$index]['text'] = $this->getUserParameterOptionTitle($object->__key);
            $index++;
        }

        return [
            'list' => $list
        ];
    }

    public function getUserParameterOptionTitle($key) {
        $option_title = '';
        $parts = explode('.', $key);
        $parent_key = '';

        foreach ($parts as $part) {
            if ('' != $parent_key) {
                $parent_key .= '.';
            }

            $parent_key .= $part;

            $title = $this->getUserParameterTitle($parent_key);

            if ('' != $option_title) {
                $option_title .= ' / ';
            }

            $option_title .= $title;
        }

        return $option_title;
    }

    public function getUserParameterTitle($key) {
        $element_title = '';

        $object = AdminLTEUserConfig::where('deleted', 0)->where('__key', $key)->first();

        if (null !== $object) {
            $element_title = $object->title;
        }

        return $element_title;
    }

    public function get_custom_variable_options(Request $request)
    {
        $parameters = $request->route()->parameters();
        $currentUser = auth()->guard('adminlteuser')->user();

        $objectList = AdminLTECustomVariable::where('deleted', 0)
            ->where('adminlteusergroup_id', $currentUser->adminlteusergroup_id)
            ->get();

        $list = [];
        $index = 0;

        foreach ($objectList as $object) {
            $list[$index]['id'] = 'CustomVariables/' . $object->name;
            $list[$index]['text'] = $object->title;
            $index++;
        }

        return [
            'list' => $list
        ];
    }
}
