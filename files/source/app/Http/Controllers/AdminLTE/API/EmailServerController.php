<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE;
use App\AdminLTEUser;
use App\Http\Requests\AdminLTE\API\EmailServerPOSTRequest;

class EmailServerController extends Controller
{
    public function get(Request $request)
    {

        return [
            'email_type' => 1,
            'email_format' => 0,
            'email_from_name' => config('mail.from.name'),
            'email_reply_to' => config('mail.from.address'),
            'email_smtp_host' => config('mail.host'),
            'email_smtp_user' => config('mail.username'),
            'email_smtp_password' => config('mail.password'),
            'email_smtp_encryption' => config('mail.encryption'),
            'email_smtp_port' => config('mail.port')
        ];

    }

    public function post(EmailServerPOSTRequest $request)
    {

        $adminLTE = new AdminLTE();
        $adminLTE->updateDotEnv(
                'MAIL_FROM_NAME',
                $request->input('email_from_name'));

        $adminLTE->updateDotEnv(
                'MAIL_FROM_ADDRESS',
                $request->input('email_reply_to'));

        $adminLTE->updateDotEnv(
                'MAIL_HOST',
                $request->input('email_smtp_host'));

        $adminLTE->updateDotEnv(
                'MAIL_USERNAME',
                $request->input('email_smtp_user'));

        $adminLTE->updateDotEnv(
                'MAIL_PASSWORD',
                $request->input('email_smtp_password'));

        $adminLTE->updateDotEnv(
                'MAIL_ENCRYPTION',
                $request->input('email_smtp_encryption'));

        $adminLTE->updateDotEnv(
                'MAIL_PORT',
                $request->input('email_smtp_port'));

        return [
            'message' => __('Email server configuration saved.')
        ];

    }

}
