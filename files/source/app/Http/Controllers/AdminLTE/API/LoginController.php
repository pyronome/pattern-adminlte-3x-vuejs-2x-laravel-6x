<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\Http\Requests\AdminLTE\API\LoginPOSTRequest;

class LoginController extends Controller
{

    public function post(LoginPOSTRequest $request)
    {
        /* {{@snippet:begin_login_post}} */
        $adminLTEUser = AdminLTEUser::where(
                'email',
                $request->input('email'))
                ->first();

        auth()->guard('adminlteuser')->login($adminLTEUser);

        $landingPage = config('adminlte.landing_page');

        if ($landingPage === false)
        {
            $landingPage = 'home';
        } // if ($landingPage === false)

        return [
            'landing_page' => $landingPage
        ];
        
         /* {{@snippet:end_login_post}} */
    }

}
