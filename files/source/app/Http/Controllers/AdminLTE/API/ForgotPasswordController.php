<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\Http\Requests\AdminLTE\API\ForgotPasswordPOSTRequest;

class ForgotPasswordController extends Controller
{
    public function post(ForgotPasswordPOSTRequest $request)
    {
        $objectAdminLTE = new AdminLTE();
  
        $email = $request->input('email');
        $objectAdminLTEUser = AdminLTEUser::where('email', $email)->first();

        $params = array();
        $params['emailFromName'] = config('mail.from.name');
        $params['name'] = $objectAdminLTEUser->fullname;
        $params['emailAddress'] = $objectAdminLTEUser->email;
        $params['newPassword'] = $objectAdminLTE->resetUserPassword($objectAdminLTEUser);
        $params['emailReplyTo'] = config('mail.from.address');

        

        Mail::send("email.forgotpassword", $params, function ($message) use ($params) {
            $message->to($params['emailAddress'], $params['name'])->subject("Your New Password");
            $message->from($params['emailReplyTo'], $params['emailFromName']);
        });

        return ['message' => "Success"];
    }
}
