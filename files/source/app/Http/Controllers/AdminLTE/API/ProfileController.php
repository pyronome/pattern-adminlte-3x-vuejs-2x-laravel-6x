<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\AdminLTE\AdminLTEUserGroup;
use App\Http\Requests\AdminLTE\API\ProfilePOSTRequest;

class ProfileController extends Controller
{

    public function get(Request $request)
    {
        $objectAdminLTE = new AdminLTE();
        $userData = $objectAdminLTE->getUserData();

        $data = [];

        if ($userData['id'] > 0)
        {
            $objectAdminLTEUser = AdminLTEUSer::find($userData['id']);

            if ($objectAdminLTEUser != null)
            {
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
            } // if ($adminLTEUser != null)
        } // if ($userData['id'] > 0)

        return [
            'object' => $data
        ];
    }

    public function get_files(Request $request) {
        $list = [];
        
        $objectAdminLTE = new AdminLTE();
        $userData = $objectAdminLTE->getUserData();

        if ($userData['id'] > 0)
        { 
            $files = $objectAdminLTE->get_model_files('AdminLTEUser', $userData['id']);
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
        }

        return [
            'list' => $list
        ];
    }

    public function post(ProfilePOSTRequest $request)
    {
        $adminLTE = new AdminLTE();
        $userData = $adminLTE->getUserData();
        $objectAdminLTEUser = AdminLTEUser::find($userData['id']);

        if ($objectAdminLTEUser != null)
        {
            $objectAdminLTEUser->fullname = $request->input('fullname');
            
            $objectAdminLTEUser->username = $request->input('username');

            $objectAdminLTEUser->email = $request->input('email');
            
            if ('' != $request->input('password1')) {
                $objectAdminLTEUser->password = bcrypt($request->input('password1'));
            } 

            $profile_img = $request->input('profile_img');

            $objectAdminLTEUser->save();
            
            $objectAdminLTE = new AdminLTE();
            $objectAdminLTE->updateModelFileObject('AdminLTEUser', $objectAdminLTEUser->id, 'profile_img', $profile_img);
        }

        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

}
