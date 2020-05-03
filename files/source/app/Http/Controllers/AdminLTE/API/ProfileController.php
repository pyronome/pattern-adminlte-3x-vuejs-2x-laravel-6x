<?php

namespace App\Http\Controllers\AdminLTE\HTMLDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE;
use App\AdminLTEUser;
use App\AdminLTEUserGroup;
use App\HTMLDB;

class ProfileController extends Controller
{
    public $columns = [];
    public $protectedColumns = [];
    public $row = [];

    public function get(Request $request)
    {

        $this->columns = [
            'id',
            'deleted',
            'created_at',
            'updated_at',
            'enabled',
            'adminlteusergroup_id',
            'adminlteusergroup_idDisplayText',
            'fullname',
            'username',
            'email',
            'password',
            'menu_permission',
            'service_permission',
            'group_menu_permission',
            'group_service_permission',
            'profile_img'
        ];

        $adminLTE = new AdminLTE();
        $userData = $adminLTE->getUserData();

        $list = [];

        if ($userData['id'] > 0)
        {
            $adminLTEUser = \App\AdminLTEUSer::find($userData['id']);

            if ($adminLTEUser != null)
            {
                $list[0]['id'] = $userData['id'];
                $list[0]['deleted'] = $userData['deleted'];
                $list[0]['created_at'] = $userData['created_at'];
                $list[0]['updated_at'] = $userData['updated_at'];
                $list[0]['enabled'] = $userData['enabled'];
                $list[0]['adminlteusergroup_id'] = $userData['adminlteusergroup_id'];
                $list[0]['adminlteusergroup_idDisplayText'] = '';
                $list[0]['fullname'] = $userData['fullname'];
                $list[0]['username'] = $userData['username'];
                $list[0]['email'] = $userData['email'];
                $list[0]['password'] = '';
                $list[0]['profile_img'] = $userData['image'];
                $list[0]['menu_permission'] = $adminLTE->base64Decode(
                        $adminLTEUser->menu_permission);
                $list[0]['service_permission'] = $adminLTE->base64Decode(
                        $adminLTEUser->service_permission);

                $list[0]['group_menu_permission'] = '';
                $list[0]['group_service_permission'] = '';

                if ($userData['adminlteusergroup_id'] > 0)
                {
                    $adminLTEUserGroup = \App\AdminLTEUserGroup::find(
                            $userData['adminlteusergroup_id']);

                    if ($adminLTEUserGroup != null)
                    {
                        $list[0]['group_menu_permission'] = $adminLTE->base64Decode(
                                $adminLTEUserGroup->menu_permission);
                        $list[0]['group_service_permission'] = $adminLTE->base64Decode(
                                $adminLTEUserGroup->menu_permission);
                    } // if ($adminLTEUserGroup != null)
                } // if ($userData['adminlteusergroup_id'] > 0)
            } // if ($adminLTEUser != null)
        } // if ($userData['id'] > 0)

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }

    public function get_form_values(Request $request)
    {
        $this->columns = [
            'id',
            'fullname',
            'username',
            'email',
            'password0',
            'password1',
            'password2',
            'profile_img'
        ];

        $adminLTE = new AdminLTE();
        $userData = $adminLTE->getUserData();

        $list = [];

        $list[0]['id'] = $userData['id'];
        $list[0]['fullname'] = $userData['fullname'];
        $list[0]['username'] = $userData['username'];
        $list[0]['email'] = $userData['email'];
        $list[0]['profile_img'] = $userData['image'];
        $list[0]['password0'] = '';
        $list[0]['password1'] = '';
        $list[0]['password2'] = '';

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }

    public function post(Request $request)
    {

        $this->columns = [
            'id',
            'fullname',
            'username',
            'email',
            'password0',
            'password1',
            'password2',
            'profile_img'
        ];

        $objectHTMLDB = new HTMLDB();

        $this->row = $objectHTMLDB->requestPOSTRow(
                $request->all(),
                $this->columns,
                $this->protectedColumns,
                0,
                true);

        $result = $this->check();

        if (0 == $result['errorCount']) {

            $adminLTE = new AdminLTE();
            $userData = $adminLTE->getUserData();

            $adminLTEUser = \App\AdminLTEUser::find($userData['id']);

            if ($adminLTEUser != null)
            {
                $adminLTEUser->fullname = $this->row['fullname'];
                $adminLTEUser->username = $this->row['username'];
                $adminLTEUser->email = $this->row['email'];

                if (($this->row['password0'] != '')
                        && ($this->row['password1'] != '')
                        && ($this->row['password2'] != ''))
                {
                    $adminLTEUser->password = bcrypt($this->row['password2']);
                } // if (($this->row['password0'] != '')

                $adminLTEUser->update();
            } // if ($adminLTEUser != null)

            $result['messageCount'] = 1;
            $result['lastMessage'] = 'UPDATED';
        } // if (0 == $result['errorCount']) {

        $objectHTMLDB->errorCount = $result['errorCount'];
        $objectHTMLDB->messageCount = $result['messageCount'];
        $objectHTMLDB->lastError = $result['lastError'];
        $objectHTMLDB->lastMessage = $result['lastMessage'];
        $objectHTMLDB->printResponseJSON();
        return;

    }

    public function check()
    {

        $result = [
            'lastError' => '',
            'errorCount' => 0,
            'lastMessage' => '',
            'messageCount' => 0
        ];

        /* {{snippet:begin_check_values}} */

        $adminLTE = new AdminLTE();
        $userData = $adminLTE->getUserData();

        if (0 == $userData['id'])
        {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('User not found.');
        }

        if ('' == $this->row['fullname'])
        {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('Please specify your fullname.');
        } // if ('' == $this->row['fullname'])

        if ('' == $this->row['username'])
        {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('Please specify username.');
        }
        else
        {
            $otherUser = \App\AdminLTEUser::where('deleted', false)
                    ->where('username', $this->row['username'])
                    ->where('id', '!=', $userData['id'])
                    ->first();

            if ($otherUser != null)
            {
                $result['errorCount']++;
                if ($result['lastError'] != '') {
                    $result['lastError'] .= '<br>';
                } // if ($result['lastError'] != '') {

                $result['lastError'] .= __('Username specified belongs to another user. Please specify another username.');
            } // if ($otherUser != null)
        } // if ('' == $this->row['username'])

        if ('' == $this->row['email'])
        {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('Please specify your email address.');
        }
        else
        {
            if (!$adminLTE->validateEmailAddress($this->row['email']))
            {
                $result['errorCount']++;
                if ($result['lastError'] != '') {
                    $result['lastError'] .= '<br>';
                } // if ($result['lastError'] != '') {

                $result['lastError'] .= __('Please specify a valid email address.');
            }
            else
            {
                $otherUser = \App\AdminLTEUser::where('deleted', false)
                        ->where('email', $this->row['email'])
                        ->where('id', '!=', $userData['id'])
                        ->first();

                if ($otherUser != null)
                {
                    $result['errorCount']++;
                    if ($result['lastError'] != '') {
                        $result['lastError'] .= '<br>';
                    } // if ($result['lastError'] != '') {

                    $result['lastError'] .= __('E-mail address specified belongs to another user. Please specify another e-mail address.');
                } // if ($otherUser != null)
            } // if (!$adminLTE->validateEmailAddress($this->row['email']))
        } // if ('' == $this->row['fullname'])
    
        if (($this->row['password1'] != '') || ($this->row['password2'] != ''))
        {
            if ('' == $this->row['password0'])
            {
                $result['errorCount']++;
                if ($result['lastError'] != '') {
                    $result['lastError'] .= '<br>';
                } // if ($result['lastError'] != '') {

                $result['lastError'] .= __('Please specify your current password.');
            }
            else
            {
                $currentUser = \App\AdminLTEUser::find($userData['id']);

                if (!password_verify($this->row['password0'],
                        $currentUser->password))
                {
                    $result['errorCount']++;
                    if ($result['lastError'] != '') {
                        $result['lastError'] .= '<br>';
                    } // if ($result['lastError'] != '') {

                    $result['lastError'] .= __('Your current password is wrong.');
                }
                else
                {
                    if ('' == $this->row['password1'])
                    {
                        $result['errorCount']++;
                        if ($result['lastError'] != '') {
                            $result['lastError'] .= '<br>';
                        } // if ($result['lastError'] != '') {

                        $result['lastError'] .= __('Please specify your new password.');
                    }
                    else if ('' == $this->row['password2'])
                    {
                        $result['errorCount']++;
                        if ($result['lastError'] != '') {
                            $result['lastError'] .= '<br>';
                        } // if ($result['lastError'] != '') {

                        $result['lastError'] .= __('Please re-enter your new password.');
                    }
                    else if ($this->row['password1'] != $this->row['password2'])
                    {
                        $result['errorCount']++;
                        if ($result['lastError'] != '') {
                            $result['lastError'] .= '<br>';
                        } // if ($result['lastError'] != '') {

                        $result['lastError'] .= __('Your new passwords are not matched. Please check your new passwords and try again.');
                    }
                } // if (password_verify($this->row['password'],
            }
        } // if ('' == $this->row['fullname'])

        /* {{snippet:end_check_values}} */

        return $result;

    }

}
