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
            $objectAdminLTEUser = AdminLTEUser::where('id', $id)->where('deleted', 0)->first();
        }
        
        $User = auth()->guard('adminlteuser')->user();

        if ($User->can('viewAny', AdminLTEUser::class) && $User->can('view', $objectAdminLTEUser)) {
            $data['user_can_create'] = $User->can('create', AdminLTEUser::class);
            $data['user_can_read'] = $User->can('view', $objectAdminLTEUser);
            $data['user_can_update'] = $User->can('update', $objectAdminLTEUser);
            $data['user_can_delete'] = $User->can('delete', $objectAdminLTEUser);
            $data['user_can_view'] = $User->can('viewAny', $objectAdminLTEUser);

            $objectAdminLTE = new AdminLTE();

            $displayTexts = $objectAdminLTE->getObjectDisplayTexts('AdminLTEUser', $objectAdminLTEUser);

            $data['id'] = $objectAdminLTEUser->id;
            $data['id__displaytext__'] = $displayTexts['id'];
            $data['deleted'] = $objectAdminLTEUser->deleted;
            $data['deleted__displaytext__'] = $displayTexts['deleted'];
            $data['created_at'] = $objectAdminLTEUser->created_at;
            $data['created_at__displaytext__'] = $displayTexts['created_at'];
            $data['updated_at'] = $objectAdminLTEUser->updated_at;
            $data['updated_at__displaytext__'] = $displayTexts['updated_at'];
            $data['enabled'] = $objectAdminLTEUser->enabled;
            $data['enabled__displaytext__'] = $displayTexts['enabled'];
            $data['adminlteusergroup_id'] = array($objectAdminLTEUser->adminlteusergroup_id);
            $data['adminlteusergroup_id__displaytext__'] = $displayTexts['adminlteusergroup_id'];
            $data['fullname'] = $objectAdminLTEUser->fullname;
            $data['fullname__displaytext__'] = $displayTexts['fullname'];
            $data['username'] = $objectAdminLTEUser->username;
            $data['username__displaytext__'] = $displayTexts['username'];
            $data['email'] = $objectAdminLTEUser->email;
            $data['email__displaytext__'] = $displayTexts['email'];
            $data['password'] = '';
            $data['password__displaytext__'] = '******';

            $external_ids = array();
            foreach ($objectAdminLTE->get_model_files_by_property('AdminLTEUser', $objectAdminLTEUser->id, 'profile_img') as $fileData) {
                $external_ids[] = $fileData['id'];
            }

            if(empty($external_ids)){
                $current_external_value = '';
            } else {
                $current_external_value = implode(',', $external_ids);
            }

            $data['profile_img'] = $current_external_value;
            $data['profile_img__displaytext__'] = $displayTexts['profile_img'];
        } // if (null !== $objectAdminLTEUser) {

        return [
            'object' => $data
        ];
    }

    public function post(AdminLTEUserPOSTRequest $request)
    {
        $User = auth()->guard('adminlteuser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $id = intval($request->input('id'));

        if ($id > 0) {
            $objectAdminLTEUser = AdminLTEUser::find($id);
            if (!$User->can('update', $objectAdminLTEUser)) {
                $has_error = true;
                $error_msg = __('You can not update this object. Contact your system administrator for more information.');
            }
        } else {
            $objectAdminLTEUser = new AdminLTEUser();
            if (!$User->can('create', AdminLTEUser::class)) {
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
 
        $return_data['id'] = $objectAdminLTEUser->id;
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
        
        $objects = AdminLTEUser::where('deleted', false)
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