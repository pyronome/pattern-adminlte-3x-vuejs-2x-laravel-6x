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
        $variables['MAIL_HOST'] = $request->input('email_smtp_host');
        $variables['MAIL_PORT'] = $request->input('email_smtp_port');
        $variables['MAIL_FROM_ADDRESS'] = $request->input('email_reply_to');
        $variables['MAIL_FROM_NAME'] = $request->input('email_from_name');
        $variables['MAIL_ENCRYPTION'] = $request->input('email_smtp_encryption');
        $variables['MAIL_USERNAME'] = $request->input('email_smtp_user');
        $variables['MAIL_PASSWORD'] = $request->input('email_smtp_password'); 

        
        $root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
        $source_path = $root . '/config/mail.template.php';
        $destination_path = $root . '/config/mail.php';
       
        $objectAdminLTE = new AdminLTE();
        $objectAdminLTE->writeTemplateFileToTarget($source_path, $destination_path, $variables);
  
        return ['message' => "Success"];
    }
}