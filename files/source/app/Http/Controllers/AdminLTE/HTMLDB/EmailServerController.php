<?php

namespace App\Http\Controllers\AdminLTE\HTMLDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\HTMLDB\HTMLDB;

class EmailServerController extends Controller
{
    public $columns = [
        'id',
        'email_from_name',
        'email_reply_to',
        'email_smtp_host',
        'email_smtp_user',
        'email_smtp_password',
        'email_smtp_encryption',
        'email_smtp_port'
    ];
    public $protectedColumns = [];
    public $row = [];

    public function get(Request $request)
    {

        $list = [];

        $list[0]['id'] = 1;
        $list[0]['email_from_name'] = config('mail.from.name');
        $list[0]['email_reply_to'] = config('mail.from.address');
        $list[0]['email_smtp_host'] = config('mail.host');
        $list[0]['email_smtp_user'] = config('mail.username');
        $list[0]['email_smtp_password'] = config('mail.password');
        $list[0]['email_smtp_encryption'] = config('mail.encryption');
        $list[0]['email_smtp_port'] = config('mail.port');

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->list = $list;
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

            $objectAdminLTE = new AdminLTE();
            $objectAdminLTE->updateDotEnv(
                    'MAIL_FROM_NAME',
                    $this->row['email_from_name']);

            $objectAdminLTE->updateDotEnv(
                    'MAIL_FROM_ADDRESS',
                    $this->row['email_reply_to']);

            $objectAdminLTE->updateDotEnv(
                    'MAIL_HOST',
                    $this->row['email_smtp_host']);

            $objectAdminLTE->updateDotEnv(
                    'MAIL_USERNAME',
                    $this->row['email_smtp_user']);

            $objectAdminLTE->updateDotEnv(
                    'MAIL_PASSWORD',
                    $this->row['email_smtp_password']);

            $objectAdminLTE->updateDotEnv(
                    'MAIL_ENCRYPTION',
                    $this->row['email_smtp_encryption']);

            $objectAdminLTE->updateDotEnv(
                    'MAIL_PORT',
                    $this->row['email_smtp_port']);

        } // if (0 == $result['errorCount'])

        if (0 == $result['errorCount']) {
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

        if ('' == $this->row['email_from_name']) {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('Please specify email from name.');
        } // if ('' == $this->row['email_from_name']) {

        if ('' == $this->row['email_reply_to']) {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('Please specify email reply to.');
        } // if ('' == $this-row['email_reply_to']) {

        if ('' == $this->row['email_smtp_host']) {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('Please specify SMTP server.');
        } // if ('' == $this->row['email_smtp_host']) {
        
        if ('' == $this->row['email_smtp_user']) {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('Please specify SMTP user.');
        } // if ('' == $this->row['email_smtp_user']) {

        if ('' == $this->row['email_smtp_host']) {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('Please specify SMTP password.');
        } // if ('' == $this->row['email_smtp_host']) {
        
        if (0 == $this->row['email_smtp_port']) {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('Please specify SMTP port.');
        } // if (0 == $this->row['email_smtp_port']) {

        /* {{snippet:end_check_values}} */

        return $result;

    }

}
