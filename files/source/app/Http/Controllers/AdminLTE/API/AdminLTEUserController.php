<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEModelOption;
use App\AdminLTE\AdminLTEUserGroup;
use App\AdminLTE\AdminLTEUser;
use App\AdminLTE\AdminLTEPermission;
use App\Http\Requests\AdminLTE\API\AdminLTEUserPOSTRequest;

class AdminLTEUserController extends Controller
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
            $objectAdminLTEUser = AdminLTEUser::where('id', $id)->where('deleted', 0)->first();
        }

        if (null !== $objectAdminLTEUser) {
            $objectAdminLTE = new AdminLTE();

            $displayTexts = $objectAdminLTE->getObjectDisplayTexts('AdminLTEUser', $objectAdminLTEUser);

            $list['id'] = $objectAdminLTEUser->id;
            $list['id__displaytext__'] = $displayTexts['id'];
            $list['deleted'] = $objectAdminLTEUser->deleted;
            $list['deleted__displaytext__'] = $displayTexts['deleted'];
            $list['created_at'] = $objectAdminLTEUser->created_at;
            $list['created_at__displaytext__'] = $displayTexts['created_at'];
            $list['updated_at'] = $objectAdminLTEUser->updated_at;
            $list['updated_at__displaytext__'] = $displayTexts['updated_at'];
            $list['enabled'] = $objectAdminLTEUser->enabled;
            $list['enabled__displaytext__'] = $displayTexts['enabled'];
            $list['adminlteusergroup_id'] = array($objectAdminLTEUser->adminlteusergroup_id);
            $list['adminlteusergroup_id__displaytext__'] = $displayTexts['adminlteusergroup_id'];
            $list['fullname'] = $objectAdminLTEUser->fullname;
            $list['fullname__displaytext__'] = $displayTexts['fullname'];
            $list['username'] = $objectAdminLTEUser->username;
            $list['username__displaytext__'] = $displayTexts['username'];
            $list['email'] = $objectAdminLTEUser->email;
            $list['email__displaytext__'] = $displayTexts['email'];
            $list['password'] = '';
            $list['password__displaytext__'] = '******';

            $external_ids = array();
            foreach ($objectAdminLTE->get_model_files_by_property('AdminLTEUser', $objectAdminLTEUser->id, 'profile_img') as $fileData) {
                $external_ids[] = $fileData['id'];
            }

            if(empty($external_ids)){
                $current_external_value = '';
            } else {
                $current_external_value = implode(',', $external_ids);
            }

            $list['profile_img'] = $current_external_value;
            $list['profile_img__displaytext__'] = $displayTexts['profile_img'];
        } // if (null !== $objectAdminLTEUser) {

        return [
            'list' => $list
        ];
    }

    public function post(AdminLTEUserPOSTRequest $request)
    {
        $id = intval($request->input('id'));

        if ($id > 0) {
            $objectAdminLTEUser = AdminLTEUser::find($id);
        } else {
            $objectAdminLTEUser = new AdminLTEUser();
        } // if ($id > 0) {
        
        $objectAdminLTEUser->deleted = 0;
        
        $objectAdminLTEUser->enabled = ('' != $request->input('enabled'))
            ? intval($request->input('enabled'))
            : 0;

        if ('' != $request->input('adminlteusergroup_id')) {
            $objectAdminLTEUser->adminlteusergroup_id = intval($request->input('adminlteusergroup_id'));
        }

        $objectAdminLTEUser->fullname = $request->input('fullname');
        
        $objectAdminLTEUser->username = $request->input('username');

        $objectAdminLTEUser->email = $request->input('email');
        
        if ('' != $request->input('password')) {
            $objectAdminLTEUser->password = bcrypt($request->input('password'));
        } 

        $profile_img = $request->input('profile_img');

        $objectAdminLTEUser->save();
        
        $objectAdminLTE = new AdminLTE();
        $objectAdminLTE->updateModelFileObject('AdminLTEUser', $objectAdminLTEUser->id, 'profile_img', $profile_img);
 
        return [
            'id' => $objectAdminLTEUser->id
        ];
    }

    public function delete(Request $request)
    {
        $selected_ids = $request->input('selected_ids');
        
        $objects = AdminLTEUser::where('deleted', false)
                ->whereIn('id', $selected_ids)
                ->get();

        foreach ($objects as $object)
        {
            $object->deleted = 1;
            $object->save();                
        } // foreach ($objects as $object)

        return ['message' => "Success"];
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
        
        $objectAdminLTE = new AdminLTE();
        $files = $objectAdminLTE->get_model_files('AdminLTEUser', $object_id);
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
        $objectAdminLTEUser = AdminLTEUser::find($id);

        if (null !== $objectAdminLTEUser) {
            $form_data['user_id'] = $id;
            $form_data['fullname'] = $objectAdminLTEUser->fullname;
            $form_data['permission_data'] = $objectAdminLTE->getUserPermissions($id);

            $objectAdminLTEUserGroup = AdminLTEUserGroup::find($objectAdminLTEUser->adminlteusergroup_id);

            if (null !== $objectAdminLTEUserGroup) {
                $form_data['title'] = $objectAdminLTEUserGroup->title;
                $form_data['group_permission_data'] = $objectAdminLTE->getUserGroupPermissions($objectAdminLTEUserGroup->id);
            }
        }

        return [
            'form_data' => $form_data
        ];
    }

    public function post_permission_data(Request $request)
    {
        $user_id = intval($request->input('user_id'));
        $permission_data_list = $request->input('permission_data');

        $objectAdminLTE = new AdminLTE();

        foreach ($permission_data_list as $permission_data) {
            $meta_key = $permission_data['meta_key'];
            $encodedPermissions = $objectAdminLTE->base64encode(json_encode($permission_data['permissions'], (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS)));
            $objectPermission = AdminLTEPermission::where('deleted', false)
                ->where('user_id', $user_id)
                ->where('meta_key', $meta_key)
                ->first();

            if (null === $objectPermission) {
                $objectPermission = new AdminLTEPermission();
                $objectPermission->user_id = $user_id;
                $objectPermission->meta_key = $meta_key;
            }
            
            $objectPermission->permissions = $encodedPermissions;
            $objectPermission->save();
        }
        
        return [
            'id' => $user_id
        ];
    }
}