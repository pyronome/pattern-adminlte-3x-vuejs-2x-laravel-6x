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
        $list = [];
        
        $parameters = $request->route()->parameters();

        $id = isset($parameters['id'])
            ? intval($parameters['id'])
            : 0;


        if (0 == $id) {
            return;
        } // if (!isset($parameters['id'])) {
        
        // is new ?
        if ('new' == htmlspecialchars($parameters['id'])) {
            return;
        } // if (isset($parameters['id']) && ('new' == htmlspecialchars($parameters['id']))) {

        if ($id > 0) {
            $objectAdminLTEUserGroup = AdminLTEUserGroup::where('id', $id)->where('deleted', 0)->first();
        }

        if (null !== $objectAdminLTEUserGroup) {
            $objectAdminLTE = new AdminLTE();
  
            $displayTexts = $objectAdminLTE->getObjectDisplayTexts('AdminLTEUserGroup', $objectAdminLTEUserGroup);

            $list['id'] = $objectAdminLTEUserGroup->id;
            $list['id__displaytext__'] = $displayTexts['id'];
            $list['deleted'] = $objectAdminLTEUserGroup->deleted;
            $list['deleted__displaytext__'] = $displayTexts['deleted'];
            $list['created_at'] = $objectAdminLTEUserGroup->created_at;
            $list['created_at__displaytext__'] = $displayTexts['created_at'];
            $list['updated_at'] = $objectAdminLTEUserGroup->updated_at;
            $list['updated_at__displaytext__'] = $displayTexts['updated_at'];
            $list['enabled'] = $objectAdminLTEUserGroup->enabled;
            $list['enabled__displaytext__'] = $displayTexts['enabled'];
            $list['admin'] = $objectAdminLTEUserGroup->admin;
            $list['admin__displaytext__'] = $displayTexts['admin'];
            $list['title'] = $objectAdminLTEUserGroup->title;
            $list['title__displaytext__'] = $displayTexts['title'];
            $list['widget_permission'] = $objectAdminLTEUserGroup->widget_permission;
            $list['widget_permission__displaytext__'] = $displayTexts['widget_permission'];
        } // if (null !== $objectAdminLTEUserGroup) {

        return [
            'list' => $list
        ];
    }

    public function post(AdminLTEUserGroupPOSTRequest $request)
    {
        $id = intval($request->input('id'));

        if ($id > 0) {
            $objectAdminLTEUserGroup = AdminLTEUserGroup::find($id);
        } else {
            $objectAdminLTEUserGroup = new AdminLTEUserGroup();
        } // if ($id > 0) {
        
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
        
        return [
            'id' => $objectAdminLTEUserGroup->id
        ];
    }

    public function delete(Request $request)
    {
        $selected_ids = $request->input('selected_ids');
        
        $objects = AdminLTEUserGroup::where('deleted', false)
                ->whereIn('id', $selected_ids)
                ->get();

        foreach ($objects as $object)
        {
            $object->deleted = 1;
            $object->save();                
        } // foreach ($objects as $object)

        return ['message' => "Success"];
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