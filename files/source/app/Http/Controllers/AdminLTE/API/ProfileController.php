<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\AdminLTE\AdminLTEUserGroup;

class ProfileController extends Controller
{

    public function get(Request $request)
    {

        $adminLTE = new AdminLTE();
        $userData = $adminLTE->getUserData();

        $response = [];

        if ($userData['id'] > 0)
        {
            $adminLTEUser = \App\AdminLTE\AdminLTEUSer::find($userData['id']);

            if ($adminLTEUser != null)
            {
                $response['id'] = $userData['id'];
                $response['deleted'] = $userData['deleted'];
                $response['created_at'] = $userData['created_at'];
                $response['updated_at'] = $userData['updated_at'];
                $response['enabled'] = $userData['enabled'];
                $response['adminlteusergroup_id'] = $userData['adminlteusergroup_id'];
                $response['adminlteusergroup_idDisplayText'] = '';
                $response['fullname'] = $userData['fullname'];
                $response['username'] = $userData['username'];
                $response['email'] = $userData['email'];
                $response['password'] = '';
                $response['profile_img'] = $userData['image'];
                $response['menu_permission'] = $adminLTE->base64Decode(
                        $adminLTEUser->menu_permission);
                $response['service_permission'] = $adminLTE->base64Decode(
                        $adminLTEUser->service_permission);

                $response['group_menu_permission'] = '';
                $response['group_service_permission'] = '';

                if ($userData['adminlteusergroup_id'] > 0)
                {
                    $adminLTEUserGroup = \App\AdminLTE\AdminLTEUserGroup::find(
                            $userData['adminlteusergroup_id']);

                    if ($adminLTEUserGroup != null)
                    {
                        $response['group_menu_permission'] = $adminLTE->base64Decode(
                                $adminLTEUserGroup->menu_permission);
                        $response['group_service_permission'] = $adminLTE->base64Decode(
                                $adminLTEUserGroup->menu_permission);
                    } // if ($adminLTEUserGroup != null)
                } // if ($userData['adminlteusergroup_id'] > 0)
            } // if ($adminLTEUser != null)
        } // if ($userData['id'] > 0)

        return $response;
    }

    public function get_form_values(Request $request)
    {
        $adminLTE = new AdminLTE();
        $userData = $adminLTE->getUserData();

        $response = [];

        $response['id'] = $userData['id'];
        $response['fullname'] = $userData['fullname'];
        $response['username'] = $userData['username'];
        $response['email'] = $userData['email'];
        $response['profile_img'] = $userData['image'];
        $response['password0'] = '';
        $response['password1'] = '';
        $response['password2'] = '';

        return $response;
    }

    public function post(ProfilePOSTRequest $request)
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
    }

}
