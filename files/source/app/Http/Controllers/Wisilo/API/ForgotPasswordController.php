<?php

namespace App\Http\Controllers\Wisilo\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;
use App\Http\Requests\Wisilo\API\ForgotPasswordPOSTRequest;

class ForgotPasswordController extends Controller
{
    public function post(ForgotPasswordPOSTRequest $request)
    {
        $objectWisilo = new Wisilo();
  
        $email = $request->input('email');
        $objectWisiloUser = WisiloUser::where('email', $email)->first();

        $params = array();
        $params['emailFromName'] = config('mail.from.name');
        $params['name'] = $objectWisiloUser->fullname;
        $params['emailAddress'] = $objectWisiloUser->email;
        $params['newPassword'] = $objectWisilo->resetUserPassword($objectWisiloUser);
        $params['emailReplyTo'] = config('mail.from.address');

        

        Mail::send("email.forgotpassword", $params, function ($message) use ($params) {
            $message->to($params['emailAddress'], $params['name'])->subject("Your New Password");
            $message->from($params['emailReplyTo'], $params['emailFromName']);
        });

        return ['message' => "Success"];
    }
}
