<?php

namespace App\Http\Controllers\AdminLTE\HTMLDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\AdminLTE\AdminLTEUserGroup;
use App\HTMLDB\HTMLDB;

class ProfileController extends Controller
{
    public $columns = [];
    public $protectedColumns = [];
    public $row = [];
    public $form_columns = [
        'id',
        'profile_img',
        'fullname',
        'username',
        'email',
        'password0',
        'password1',
        'password2'
    ];

    public function check()
    {

        $result = [
            'lastError' => '',
            'errorCount' => 0,
            'lastMessage' => '',
            'messageCount' => 0
        ];

        /* {{snippet:begin_check_values}} */

        $objectAdminLTE = new AdminLTE();
        $userData = $objectAdminLTE->getUserData();

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
            $otherUser = AdminLTEUser::where('deleted', false)
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
            if (!$objectAdminLTE->validateEmailAddress($this->row['email']))
            {
                $result['errorCount']++;
                if ($result['lastError'] != '') {
                    $result['lastError'] .= '<br>';
                } // if ($result['lastError'] != '') {

                $result['lastError'] .= __('Please specify a valid email address.');
            }
            else
            {
                $otherUser = AdminLTEUser::where('deleted', false)
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
            } // if (!$objectAdminLTE->validateEmailAddress($this->row['email']))
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
                $currentUser = AdminLTEUser::find($userData['id']);

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
    
    public function get(Request $request)
    {
        // start: check user get permission
        /*
        ::must_update:: servis izinleri nasıl kontrol ediliyor ?

        $directoryName = basename(dirname(__FILE__));
        $fileName = basename(__FILE__);
    
        includeLibrary('adminlte/checkUserGetPermission');
        $permissionResult = checkUserGetPermission($directoryName, $fileName);
    
        if ($permissionResult['error']) {
            $controller->errorCount = 1;
            $controller->lastError = $permissionResult['error_msg'];
            return false;
        }*/
        // end: check user get permission
        
        $this->columns = [
            'id',
            'id/display_text',
            'deleted',
            'deleted/display_text',
            'created_at',
            'created_at/display_text',
            'updated_at',
            'updated_at/display_text',
            'enabled',
            'enabled/display_text',
            'adminlteusergroup_id',
            'adminlteusergroup_id/display_text',
            'profile_img',
            'profile_img/display_text',
            'fullname',
            'fullname/display_text',
            'username',
            'username/display_text',
            'email',
            'email/display_text',
            'password'
        ];
    
        $list = [];
        
        $objectAdminLTE = new AdminLTE();
        $userData = $objectAdminLTE->getUserData();
        
        if ($userData['id'] > 0)
        {
            $objectAdminLTEUserList[] = AdminLTEUser::where('id', intval($userData['id']))->first();
        } else {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->list = $list;
            $objectHTMLDB->columns = $this->columns;
            $objectHTMLDB->printHTMLDBList();
            return;
        }

        $objectAdminLTEUser = NULL;
        $index = 0;

        foreach ($objectAdminLTEUserList as $objectAdminLTEUser)
        {
            $displayTexts = $objectAdminLTE->getObjectDisplayTexts('AdminLTEUser', $objectAdminLTEUser);

            $list[$index]['id'] = $objectAdminLTEUser->id;
            $list[$index]['id/display_text'] = $displayTexts['id'];
            $list[$index]['deleted'] = $objectAdminLTEUser->deleted;
            $list[$index]['deleted/display_text'] = $displayTexts['deleted'];
            $list[$index]['created_at'] = $objectAdminLTEUser->created_at;
            $list[$index]['created_at/display_text'] = $displayTexts['created_at'];
            $list[$index]['updated_at'] = $objectAdminLTEUser->updated_at;
            $list[$index]['updated_at/display_text'] = $displayTexts['updated_at'];
            $list[$index]['enabled'] = $objectAdminLTEUser->enabled;
            $list[$index]['enabled/display_text'] = $displayTexts['enabled'];
            $list[$index]['adminlteusergroup_id'] = $objectAdminLTEUser->adminlteusergroup_id;
            $list[$index]['adminlteusergroup_id/display_text'] = $displayTexts['adminlteusergroup_id'];

            $external_ids = array();
            foreach ($objectAdminLTEUser->get_files($objectAdminLTEUser->id, 'profile_img') as $fileData) {
                $external_ids[] = $fileData['id'];
            }

            if(empty($external_ids)){
                $current_external_value = '';
            } else {
                $current_external_value = implode(',', $external_ids);
            }

            $list[$index]['profile_img'] = $current_external_value;
            $list[$index]['profile_img/display_text'] = $displayTexts['profile_img'];

            $list[$index]['fullname'] = $objectAdminLTEUser->fullname;
            $list[$index]['fullname/display_text'] = $displayTexts['fullname'];
            $list[$index]['username'] = $objectAdminLTEUser->username;
            $list[$index]['username/display_text'] = $displayTexts['username'];
            $list[$index]['email'] = $objectAdminLTEUser->email;
            $list[$index]['email/display_text'] = $displayTexts['email'];
            $list[$index]['password'] = '';

            $index++;
        } // foreach ($objectAdminLTEUserList as $objectAdminLTEUser)

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }

    public function get_files(Request $request) {
        // start: check user get permission
        /*
        ::must_update:: servis izinleri nasıl kontrol ediliyor ?

        $directoryName = basename(dirname(__FILE__));
        $fileName = basename(__FILE__);
    
        includeLibrary('adminlte/checkUserGetPermission');
        $permissionResult = checkUserGetPermission($directoryName, $fileName);
    
        if ($permissionResult['error']) {
            $controller->errorCount = 1;
            $controller->lastError = $permissionResult['error_msg'];
            return false;
        }*/
        // end: check user get permission
        
        $columns = [
            'id',
            'object_property',
            'file_name',
            'path',
            'media_type',
            'extension'
        ];
    
        $list = [];
        
        $objectAdminLTE = new AdminLTE();
        $userData = $objectAdminLTE->getUserData();
        
        if (0 == $userData['id'])
        {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->list = $list;
            $objectHTMLDB->columns = $columns;
            $objectHTMLDB->printHTMLDBList();
            return;
        }

        $object_id = $userData['id'];
        
        $objectAdminLTE = new AdminLTE();
        $files = $objectAdminLTE->getModelFiles('AdminLTEUser', $object_id);
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

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }

    public function get_form_values(Request $request)
    {
        $list = [];

        $objectAdminLTE = new AdminLTE();
        $userData = $objectAdminLTE->getUserData();

        if ($userData['id'] > 0)
        {
            $objectAdminLTEUserList[] = AdminLTEUser::where('id', intval($userData['id']))->first();
        } else {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->list = $list;
            $objectHTMLDB->columns = $this->form_columns;
            $objectHTMLDB->printHTMLDBList();
            return;
        }

        $objectAdminLTEUser = NULL;
        $index = 0;

        foreach ($objectAdminLTEUserList as $objectAdminLTEUser)
        {
            $displayTexts = $objectAdminLTE->getObjectDisplayTexts('AdminLTEUser', $objectAdminLTEUser);

            $list[$index]['id'] = $objectAdminLTEUser->id;
            $list[$index]['fullname'] = $objectAdminLTEUser->fullname;
            $list[$index]['username'] = $objectAdminLTEUser->username;
            $list[$index]['email'] = $objectAdminLTEUser->email;
            $list[$index]['password0'] = '';
            $list[$index]['password1'] = '';
            $list[$index]['password2'] = '';

            $external_ids = array();
            foreach ($objectAdminLTEUser->get_files($objectAdminLTEUser->id, 'profile_img') as $fileData) {
                $external_ids[] = $fileData['id'];
            }

            if(empty($external_ids)){
                $current_external_value = '';
            } else {
                $current_external_value = implode(',', $external_ids);
            }

            $list[$index]['profile_img'] = $current_external_value;

            $index++;
        } // foreach ($objectAdminLTEUserList as $objectAdminLTEUser)

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->form_columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }

    public function post(Request $request)
    {
        $objectHTMLDB = new HTMLDB();

        $this->row = $objectHTMLDB->requestPOSTRow(
                $request->all(),
                $this->form_columns,
                $this->protectedColumns,
                0,
                true);

        $result = $this->check();

        if (0 == $result['errorCount']) {

            $objectAdminLTE = new AdminLTE();
            $userData = $objectAdminLTE->getUserData();

            $objectAdminLTEUser = AdminLTEUser::find($userData['id']);

            if ($objectAdminLTEUser != null)
            {
                $objectAdminLTEUser->fullname = $this->row['fullname'];
                $objectAdminLTEUser->username = $this->row['username'];
                $objectAdminLTEUser->email = $this->row['email'];

                if (($this->row['password0'] != '')
                        && ($this->row['password1'] != '')
                        && ($this->row['password2'] != ''))
                {
                    $objectAdminLTEUser->password = bcrypt($this->row['password2']);
                } // if (($this->row['password0'] != '')

                $objectAdminLTEUser->update();
            } // if ($objectAdminLTEUser != null)

            $profile_img = $this->row['profile_img'];

            $objectAdminLTE = new AdminLTE();
            $objectAdminLTE->updateModelFileObject('AdminLTEUser', $objectAdminLTEUser->id, 'profile_img', $profile_img);

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
}