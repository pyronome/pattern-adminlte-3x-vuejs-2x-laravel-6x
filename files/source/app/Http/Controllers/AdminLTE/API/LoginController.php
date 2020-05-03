<?php

namespace App\Http\Controllers\AdminLTE\HTMLDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE;
use App\AdminLTEUser;
use App\HTMLDB;

class LoginController extends Controller
{
    public $columns = [
        'id',
        'email',
        'password',
        'remember'
    ];

    public $protectedColumns = [];
    public $row = [];

    public function get(Request $request)
    {

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;

    }

    public function post(Request $request)
    {

        $objectHTMLDB = new HTMLDB();

        $this->row = $objectHTMLDB->requestPOSTRow(
                $request->all(),
                $this->columns,
                $this->protectedColumns,
                0,
                true);

        $result = $this->check();

        if (0 == $result['errorCount'])
        {
            $adminLTEUser = AdminLTEUser::where('email', $this->row['email'])
                    ->first();

            auth()->guard('adminlteuser')->login($adminLTEUser);

            $landingPage = config('adminlte.landing_page');

            if ($landingPage === false)
            {
                $landingPage = 'home';
            } // if ($landingPage === false)

            $result['messageCount'] = 1;
            $result['lastMessage'] = $landingPage;
        } // if (0 == $result['errorCount'])

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

        if ('' == $this->row['email'])
        {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('Please specify your email address.');
        } // if ('' == $this->row['email'])

        if ('' == $this->row['password'])
        {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('Please specify your password.');
        } // if ('' == $this->row['password'])

        if (0 == $result['errorCount']) {

            $adminLTEUser = AdminLTEUser::where('email', $this->row['email'])
                    ->first();
            
            $confirmed = false;

            if ($adminLTEUser != null)
            {
                if (password_verify($this->row['password'], $adminLTEUser->password))
                {
                    $confirmed = true;
                }
            } // if (null == $adminLTEUser)

            if (!$confirmed)
            {
                $result['errorCount']++;
                if ($result['lastError'] != '') {
                    $result['lastError'] .= '<br>';
                } // if ($result['lastError'] != '') {

                $result['lastError'] .= __('Your e-mail address or password is not correct. Please check and try again.');

                sleep(2);
            } // if (!$confirmed)

        }



        /* {{snippet:end_check_values}} */

        return $result;

    }

}
