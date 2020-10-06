<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
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
        $variables = array();
        $variables['mail.from.name'] = $request->input('email_from_name');
        $variables['mail.from.address'] = $request->input('email_reply_to');
        $variables['mail.host'] = $request->input('email_smtp_host');
        $variables['mail.username'] = $request->input('email_smtp_user');
        $variables['mail.password'] = $request->input('email_smtp_password'); 
        $variables['mail.encryption'] = $request->input('email_smtp_encryption');
        $variables['mail.port'] = $request->input('email_smtp_port');

        $root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
        $source_path = $root . '/config/mail.template.json';
        $destination_path = 'config/mail.json';
       
        $objectAdminLTE = new AdminLTE();
        $objectAdminLTE->writeTemplateFileToTarget($source_path, $destination_path, $variables);

        return ['message' => "Success"];
    }
}