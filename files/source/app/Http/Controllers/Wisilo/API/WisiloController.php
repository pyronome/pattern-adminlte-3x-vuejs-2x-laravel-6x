<?php

namespace App\Http\Controllers\Wisilo\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;
use App\Wisilo\WisiloUserGroup;
use App\Wisilo\WisiloCustomVariable;
use App\Wisilo\WisiloCustomVariableValue;
use App\Wisilo\WisiloConfig;
use App\Wisilo\WisiloUserConfig;
use App\Wisilo\WisiloLayout;
use App\Wisilo\WisiloWidgetHelper;

class WisiloController extends Controller
{

    public function get_page_variables(Request $request)
    {   
        $parameters = $request->route()->parameters();

        $componentName = isset($parameters['componentName'])
                ? htmlspecialchars($parameters['componentName'])
                : '';

        $objectWisilo = new Wisilo();

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
        $active_widgets = $objectWisilo->getUserWidgets($componentName);
        
        // Permissions
        $menu_permissions = $objectWisilo->getUserMenuPermissions();
        $model_permissions = $objectWisilo->getUserModelPermissions();
        $plugins_permissions = $objectWisilo->getUserPluginsPermissions();

        // customvariables
        $custom_variables = $objectWisilo->getCurrentCustomVariables();

        return [
            'is_admin' => $admin,
            'show_widget_config_button' => $show_widget_config_button,
            'menu_permissions' => $menu_permissions,
            'model_permissions' => $model_permissions,
            'plugins_permissions' => $plugins_permissions,
            'active_widgets' => $active_widgets,
            'custom_variables' => $custom_variables
        ];
    }

    public function get_custom_variables(Request $request) {
        $parameters = $request->route()->parameters();
        $currentUser = auth()->guard('wisilouser')->user();

        $objectList = WisiloCustomVariable::where('deleted', 0)
            ->where('__system', 1)
            ->where('wisilousergroup_id', 0)
            ->get();
        
        $list = [];
        $index = 0;

        foreach ($objectList as $object) {
            $list[$index]['id'] = $object->id;
            $list[$index]['__system'] = $object->__system;
            $list[$index]['group'] = $object->group;
            $list[$index]['wisilousergroup_id'] = $object->wisilousergroup_id;
            $list[$index]['title'] = $object->title;
            $list[$index]['name'] = $object->name;
            $list[$index]['value'] = $this->getCustomVariableValue($object);
            $list[$index]['default_value'] = $object->default_value;
            $list[$index]['remember'] = $object->remember;
            $list[$index]['remember_type'] = $object->remember_type;

            $index++;
        }

        $objectList = WisiloCustomVariable::where('deleted', 0)
            ->where('wisilousergroup_id', $currentUser->wisilousergroup_id)
            ->get();

        foreach ($objectList as $object) {
            $list[$index]['id'] = $object->id;
            $list[$index]['__system'] = $object->__system;
            $list[$index]['group'] = $object->group;
            $list[$index]['wisilousergroup_id'] = $object->wisilousergroup_id;
            $list[$index]['title'] = $object->title;
            $list[$index]['name'] = $object->name;
            $list[$index]['value'] = $this->getCustomVariableValue($object);
            $list[$index]['default_value'] = $object->default_value;
            $list[$index]['remember'] = $object->remember;
            $list[$index]['remember_type'] = $object->remember_type;

            $index++;
        }

        return [
            'list' => $list
        ];
    }

    public function getCustomVariableValue($objCV) {
        $customvariable_key = 'customvariable' . $objCV->id;
		$remember_type = $objCV->remember_type;
		$defaultValue = $objCV->default_value;
		$variableValue = $defaultValue;
		$remember = (1 == $objCV->remember);
        $foundValue = false;

        if ($remember) {
            if ('session' == $remember_type) {
                if (session()->has($customvariable_key)) {
                    $variableValue = session()->get($customvariable_key);
                }
            } else if ('database' == $remember_type) {
                $object = WisiloCustomVariableValue::where('deleted', 0)
                    ->where('customvariable_id', $objCV->id)
                    ->where('wisilousergroup_id', $objCV->wisilousergroup_id)
                    ->first();
    
                if (null !== $object) {
                    $variableValue = $object->value;
                }
            }
        }

        if (!$foundValue) {
            $objectWisiloWidgetHelper = new WisiloWidgetHelper();

            if (false !== strpos($defaultValue, '{{GlobalParameters')) {
                $variableValue = $objectWisiloWidgetHelper->getVariableCalculatedValue($objCV, [], [], [], []);
            } else if (false !== strpos($defaultValue, '{{UserParameters')) {
                $variableValue = $objectWisiloWidgetHelper->getVariableCalculatedValue($objCV, [], [], [], []);
            } else if (false !== strpos($defaultValue, '{{CurrentUser')) {
                $variableValue = $objectWisiloWidgetHelper->getVariableCalculatedValue($objCV, [], [], [], []);
            }
        }

        return $variableValue;
    }

    public function post_custom_variable(Request $request)
    {
        $currentUser = auth()->guard('wisilouser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $id = intval($request->input('id'));

        if ($id > 0) {
            $updatedObject = WisiloCustomVariable::find($id);
        } else {
            $updatedObject = new WisiloCustomVariable();
        } // if ($id > 0) {

        $title = $request->input('title');
        $name = $request->input('name');
        $value = $request->input('value');

        $object = WisiloCustomVariable::where('deleted', 0)
            ->where('wisilousergroup_id', $currentUser->wisilousergroup_id)
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
        
        $updatedObject->deleted = 0;
        if ($id > 0) {
            $updatedObject->updated_by = $currentUser->id;
        } else {
            $updatedObject->created_by = $currentUser->id;
            $updatedObject->updated_by = $currentUser->id;
        } // if ($id > 0) {

        $updatedObject->__system = 0;
        $updatedObject->wisilousergroup_id = $currentUser->wisilousergroup_id;         
        $updatedObject->title = $title;
        $updatedObject->name = $name;
        $updatedObject->value = $value;
        $updatedObject->default_value = $request->input('default_value');
        $updatedObject->remember = intval($request->input('remember'));
        $updatedObject->remember_type = $request->input('remember_type');
        $updatedObject->__order = 0;
        $updatedObject->save();
        
        $return_data['id'] = $updatedObject->id;
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

    public function delete_custom_variable(Request $request)
    {
        $User = auth()->guard('wisilouser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $id = $request->input('id');
        
        $object = WisiloCustomVariable::where('deleted', false)
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

        $objectList = WisiloConfig::where('deleted', 0)->get();

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

        $object = WisiloConfig::where('deleted', 0)->where('__key', $key)->first();

        if (null !== $object) {
            $element_title = $object->title;
        }

        return $element_title;
    }

    public function get_user_parameter_options(Request $request)
    {
        $parameters = $request->route()->parameters();
        $currentUser = auth()->guard('wisilouser')->user();
        $currentGroupId = $currentUser->wisilousergroup_id;

        $objectList = WisiloUserConfig::where('deleted', 0)->whereIn('owner_group', [0, $currentGroupId])->get();

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

        $object = WisiloUserConfig::where('deleted', 0)->where('__key', $key)->first();

        if (null !== $object) {
            $element_title = $object->title;
        }

        return $element_title;
    }

    public function get_custom_variable_options(Request $request)
    {
        $parameters = $request->route()->parameters();
        $currentUser = auth()->guard('wisilouser')->user();

        $objectList = WisiloCustomVariable::where('deleted', 0)
            ->where('__system', 1)
            ->where('wisilousergroup_id', 0)
            ->get();

        $list = [];
        $index = 0;

        foreach ($objectList as $object) {
            $list[$index]['id'] = 'CustomVariables/' . $object->name;
            $list[$index]['text'] = $object->title;
            $index++;
        }

        $objectList = WisiloCustomVariable::where('deleted', 0)
            ->where('wisilousergroup_id', $currentUser->wisilousergroup_id)
            ->get();

        foreach ($objectList as $object) {
            $list[$index]['id'] = 'CustomVariables/' . $object->name;
            $list[$index]['text'] = $object->title;
            $index++;
        }

        return [
            'list' => $list
        ];
    }

    public function get_impersonation_users(Request $request)
    {
        /* $parameters = $request->route()->parameters();
        $currentUser = auth()->guard('wisilouser')->user(); */

        $list = [];
        $WisiloUserGroupList = WisiloUserGroup::where('deleted', 0)->orderBy('title', 'asc')->get();

        foreach ($WisiloUserGroupList as $WisiloUserGroup) {
            $list[$WisiloUserGroup->id]['group_id'] = $WisiloUserGroup->id;
            $list[$WisiloUserGroup->id]['group_title'] = $WisiloUserGroup->title;
            $list[$WisiloUserGroup->id]['children'] = [];
        }

        $objectList = WisiloUser::where('deleted', 0)
            /* ->where('wisilousergroup_id', $currentUser->wisilousergroup_id) */
            ->orderBy('email', 'asc')
            ->get();

        /* $list = [];
        $index = 0; */

        foreach ($objectList as $object) {
            $list[$object->wisilousergroup_id]['children'][] = [
                'id' => $object->id,
                'email' => $object->email
            ];
            /* $list[$index]['id'] = $object->id;
            $list[$index]['text'] = '<b>sss' . $userGroupTitles[$object->wisilousergroup_id] . '</b>/' . $object->email;
            $index++; */
        }

        /* echo '<div style="display:block">';
        echo '<pre>';
        print_r($userGroupTitles);
        echo '</pre>';
        echo '</div>';
        die();
 */
        return [
            'list' => $list
        ];
    }

    public function get_impersonation_data() {
        $impersonated_id = 0;
        $key = sha1('wisilo_impersonate');

        if (session()->has($key)) {
            $impersonated_id = intval(session($key));
        }

        return [
            'impersonated_id' => $impersonated_id
        ];
    }

    public function post_impersonation_data(Request $request)
    {
        $currentUser = auth()->guard('wisilouser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $user_id = intval($request->input('user_id'));
        $key = sha1('wisilo_impersonate');

        if ($user_id > 0) {
            session()->forget($key);
            session()->put($key, $user_id);
        } else {
            $has_error = true;
            $error_msg = __('Please select an user for impersonation.');
        }

        if ($has_error) {
            $return_data['id'] = 1;
            $return_data['has_error'] = $has_error;
            $return_data['error_msg'] = $error_msg;

            return $return_data;
        }
        
        $return_data['id'] = 1;
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

    public function remove_impersonation(Request $request)
    {
        $currentUser = auth()->guard('wisilouser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $key = sha1('wisilo_impersonate');
        session()->forget($key);

        if ($has_error) {
            $return_data['id'] = 1;
            $return_data['has_error'] = $has_error;
            $return_data['error_msg'] = $error_msg;

            return $return_data;
        }
        
        $return_data['id'] = 1;
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

    public function get_source_widgets(Request $request)
    {   
        $parameters = $request->route()->parameters();

        $source_group_id = isset($parameters['source_group_id'])
                ? intval($parameters['source_group_id'])
                : 0;

        $widgets = [];
        
        if (0 != $source_group_id) {
            $widgets = WisiloLayout::where('deleted', false)
				->where('wisilousergroup_id', $source_group_id)
				->where('enabled', 1)
				->orderBy('pagename', 'asc')
                ->orderBy('id', 'asc')
				->get();

            if (0 == count($widgets)) {
                $widgets = [];
            }
        }
        
        return [
            'source_widgets' => $widgets
        ];
    }
    
    public function post_copy_widgets(Request $request)
    {
        $currentUser = auth()->guard('wisilouser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $source_group_id = intval($request->input('source_group_id'));
        $target_group_id = intval($request->input('target_group_id'));
        $selected_widget_ids = $request->input('selected_widget_ids');
            
        if (0 == $source_group_id) {
            $has_error = true;
            $error_msg = __('Please select source user group.');
        }

        if (0 == $target_group_id) {
            $has_error = true;

            if ('' != $error_msg) {
                $error_msg .= '<br>';
            }

            $error_msg .= __('Please select target user group.');
        }

        if (empty($selected_widget_ids)) {
            $has_error = true;

            if ('' != $error_msg) {
                $error_msg .= '<br>';
            }
            
            $error_msg .= __('Please select widgets for copy process.');
        }

        if ($has_error) {
            $return_data['id'] = 1;
            $return_data['has_error'] = $has_error;
            $return_data['error_msg'] = $error_msg;

            return $return_data;
        }
        
        $objectWisiloLayouts = WisiloLayout::where('deleted', false)
            ->whereIn('id', $selected_widget_ids)
            ->orderBy('id', 'asc')
            ->get();

        foreach ($objectWisiloLayouts as $source_layout) {
            $target_layout = new WisiloLayout();
            $target_layout->__system = $source_layout->__system;
            $target_layout->enabled = $source_layout->enabled;
            $target_layout->__order = $source_layout->__order;
            $target_layout->wisilousergroup_id = $target_group_id;
            $target_layout->pagename = $source_layout->pagename;
            $target_layout->widget = $source_layout->widget;
            $target_layout->title = $source_layout->title;
            $target_layout->grid_size = $source_layout->grid_size;
            $target_layout->icon = $source_layout->icon;
            $target_layout->meta_data_json = $source_layout->meta_data_json;
            $target_layout->data_source_json = $source_layout->data_source_json;
            $target_layout->conditional_data_json = $source_layout->conditional_data_json;
            $target_layout->save();
        }
        
        $return_data['id'] = 1;
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }
}
