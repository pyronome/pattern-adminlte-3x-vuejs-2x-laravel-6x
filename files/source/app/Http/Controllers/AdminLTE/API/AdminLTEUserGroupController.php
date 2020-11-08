<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEModelOption;
use App\AdminLTE\AdminLTEUserGroup;
use App\AdminLTE\AdminLTEPermission;
use App\Http\Requests\AdminLTE\API\AdminLTEUserGroupPOSTRequest;

class AdminLTEUserGroupController extends Controller
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
            $objectAdminLTEUserGroup = AdminLTEUserGroup::where('id', $id)->where('deleted', 0)->first();
        }
        
        $User = auth()->guard('adminlteuser')->user();

        if ($User->can('viewAny', AdminLTEUserGroup::class) && $User->can('view', $objectAdminLTEUserGroup)) {
            $data['user_can_create'] = $User->can('create', AdminLTEUserGroup::class);
            $data['user_can_read'] = $User->can('view', $objectAdminLTEUserGroup);
            $data['user_can_update'] = $User->can('update', $objectAdminLTEUserGroup);
            $data['user_can_delete'] = $User->can('delete', $objectAdminLTEUserGroup);
            $data['user_can_view'] = $User->can('viewAny', $objectAdminLTEUserGroup);

            $objectAdminLTE = new AdminLTE();
            $displayTexts = $objectAdminLTE->getObjectDisplayTexts('AdminLTEUserGroup', $objectAdminLTEUserGroup);

            $data['id'] = $objectAdminLTEUserGroup->id;
            $data['id__displaytext__'] = $displayTexts['id'];
            $data['deleted'] = $objectAdminLTEUserGroup->deleted;
            $data['deleted__displaytext__'] = $displayTexts['deleted'];
            $data['created_at'] = $objectAdminLTEUserGroup->created_at;
            $data['created_at__displaytext__'] = $displayTexts['created_at'];
            $data['updated_at'] = $objectAdminLTEUserGroup->updated_at;
            $data['updated_at__displaytext__'] = $displayTexts['updated_at'];
            $data['enabled'] = $objectAdminLTEUserGroup->enabled;
            $data['enabled__displaytext__'] = $displayTexts['enabled'];
            $data['admin'] = $objectAdminLTEUserGroup->admin;
            $data['admin__displaytext__'] = $displayTexts['admin'];
            $data['title'] = $objectAdminLTEUserGroup->title;
            $data['title__displaytext__'] = $displayTexts['title'];
            $data['widget_permission'] = $objectAdminLTEUserGroup->widget_permission;
            $data['widget_permission__displaytext__'] = $displayTexts['widget_permission'];
        } // if (null !== $objectAdminLTEUserGroup) {

        return [
            'object' => $data
        ];
    }

    public function post(AdminLTEUserGroupPOSTRequest $request)
    {
        $User = auth()->guard('adminlteuser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $id = intval($request->input('id'));

        if ($id > 0) {
            $objectAdminLTEUserGroup = AdminLTEUserGroup::find($id);
            if (!$User->can('update', $objectAdminLTEUserGroup)) {
                $has_error = true;
                $error_msg = 'You can not update this object. Contact your system administrator for more information.';
            }
        } else {
            $objectAdminLTEUserGroup = new AdminLTEUserGroup();
            if (!$User->can('create', AdminLTEUserGroup::class)) {
                $has_error = true;
                $error_msg = 'You can not create any object. Contact your system administrator for more information.';
            }
        } // if ($id > 0) {
        
        if ($has_error) {
            $return_data['id'] = $id;
            $return_data['has_error'] = $has_error;
            $return_data['error_msg'] = $error_msg;

            return $return_data;
        }
        
        $objectAdminLTEUserGroup->deleted = 0;
        $objectAdminLTEUserGroup->enabled = ('' != $request->input('enabled'))
                ? intval($request->input('enabled'))
                : 0; 
        $objectAdminLTEUserGroup->admin = ('' != $request->input('admin'))
                ? intval($request->input('admin'))
                : 0;         
        $objectAdminLTEUserGroup->title = $request->input('title');
        $objectAdminLTEUserGroup->widget_permission = ('' != $request->input('widget_permission'))
                ? intval($request->input('widget_permission'))
                : 0;

        $objectAdminLTEUserGroup->save();
        
        $return_data['id'] = $objectAdminLTEUserGroup->id;
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
        
        $objects = AdminLTEUserGroup::where('deleted', false)
                ->whereIn('id', $selected_ids)
                ->get();

        foreach ($objects as $object)
        {
            if (!$User->can('delete', $object)) {
                $has_error = true;
                $error_msg = 'You can not delete this object. Contact your system administrator for more information.';
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
            
        $return_data['has_error'] = true;
        $return_data['error_msg'] = $error_msg;

        return $return_data;
    }

    public function get_permission_data(Request $request)
    {    
        $form_data = [];
        
        $parameters = $request->route()->parameters();

        $id = isset($parameters['id'])
            ? intval($parameters['id'])
            : 0;


        if ($id <= 0) {
            return;
        } // if (!isset($parameters['id'])) {

        $objectAdminLTE = new AdminLTE();
        $objectAdminLTEUserGroup = AdminLTEUserGroup::find($id);

        if (null !== $objectAdminLTEUserGroup) {
            $form_data['usergroup_id'] = $id;
            $form_data['title'] = $objectAdminLTEUserGroup->title;
            $form_data['permission_data'] = $objectAdminLTE->getUserGroupPermissions($id);
        }

        return [
            'form_data' => $form_data
        ];
    }

    public function post_permission_data(Request $request)
    {
        $usergroup_id = intval($request->input('usergroup_id'));
        $permission_data_list = $request->input('permission_data');

        $objectAdminLTE = new AdminLTE();

        foreach ($permission_data_list as $permission_data) {
            $meta_key = $permission_data['meta_key'];
            $encodedPermissions = $objectAdminLTE->base64encode(json_encode($permission_data['permissions'], (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS)));
            $objectPermission = AdminLTEPermission::where('deleted', false)
                ->where('usergroup_id', $usergroup_id)
                ->where('meta_key', $meta_key)
                ->first();

            if (null === $objectPermission) {
                $objectPermission = new AdminLTEPermission();
                $objectPermission->usergroup_id = $usergroup_id;
                $objectPermission->meta_key = $meta_key;
            }
            
            $objectPermission->permissions = $encodedPermissions;
            $objectPermission->save();
        }
        
        return [
            'id' => $usergroup_id
        ];
    }
}