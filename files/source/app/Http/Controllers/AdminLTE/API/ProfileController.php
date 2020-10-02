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

        $response = [];

        if ($userData['id'] > 0)
        {
            $objectAdminLTEUser = AdminLTEUSer::find($userData['id']);

            if ($objectAdminLTEUser != null)
            {
                $displayTexts = $objectAdminLTE->getObjectDisplayTexts('AdminLTEUser', $objectAdminLTEUser);

                $response['id'] = $objectAdminLTEUser->id;
                $response['id__displaytext__'] = $displayTexts['id'];
                $response['deleted'] = $objectAdminLTEUser->deleted;
                $response['deleted__displaytext__'] = $displayTexts['deleted'];
                $response['created_at'] = $objectAdminLTEUser->created_at;
                $response['created_at__displaytext__'] = $displayTexts['created_at'];
                $response['updated_at'] = $objectAdminLTEUser->updated_at;
                $response['updated_at__displaytext__'] = $displayTexts['updated_at'];
                $response['enabled'] = $objectAdminLTEUser->enabled;
                $response['enabled__displaytext__'] = $displayTexts['enabled'];
                $response['adminlteusergroup_id'] = array($objectAdminLTEUser->adminlteusergroup_id);
                $response['adminlteusergroup_id__displaytext__'] = $displayTexts['adminlteusergroup_id'];
                $response['fullname'] = $objectAdminLTEUser->fullname;
                $response['fullname__displaytext__'] = $displayTexts['fullname'];
                $response['username'] = $objectAdminLTEUser->username;
                $response['username__displaytext__'] = $displayTexts['username'];
                $response['email'] = $objectAdminLTEUser->email;
                $response['email__displaytext__'] = $displayTexts['email'];
                $response['password'] = '';
                $response['password__displaytext__'] = '******';

                $external_ids = array();
                foreach ($objectAdminLTE->get_model_files_by_property('AdminLTEUser', $objectAdminLTEUser->id, 'profile_img') as $fileData) {
                    $external_ids[] = $fileData['id'];
                }

                if(empty($external_ids)){
                    $current_external_value = '';
                } else {
                    $current_external_value = implode(',', $external_ids);
                }

                $response['profile_img'] = $current_external_value;
                $response['profile_img__displaytext__'] = $displayTexts['profile_img'];
            } // if ($adminLTEUser != null)
        } // if ($userData['id'] > 0)

        return $response;
    }

    public function get_files(Request $request) {
        $list = [];
        
        $objectAdminLTE = new AdminLTE();
        $userData = $objectAdminLTE->getUserData();

        $response = [];

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

    /* public function post(ProfilePOSTRequest $request)
    {
        $adminLTE = new AdminLTE();
        $userData = $adminLTE->getUserData();

        $adminLTEUser = \App\AdminLTE\AdminLTEUser::find($userData['id']);

        if ($adminLTEUser != null)
        {
            $adminLTEUser->fullname = $request->input('fullname');
            $adminLTEUser->username = $request->input('username');
            $adminLTEUser->email = $request->input('email');

            if (($request->input('password0') != '')
                    && ($request->input('password1') != '')
                    && ($request->input('password2') != ''))
            {
                $adminLTEUser->password = bcrypt($request->input('password2'));
            } // if (($request->input('password0') != '')

            $adminLTEUser->update();
        } // if ($adminLTEUser != null)

        $result['message'] = 'UPDATED';

        return $result;
    } */

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
            
            if ('' != $request->input('password')) {
                $objectAdminLTEUser->password = bcrypt($request->input('password'));
            } 

            $profile_img = $request->input('profile_img');

            $objectAdminLTEUser->save();
            
            $objectAdminLTE = new AdminLTE();
            $objectAdminLTE->updateModelFileObject('AdminLTEUser', $objectAdminLTEUser->id, 'profile_img', $profile_img);
        }

        return ['message' => "Success"];
    }

}
