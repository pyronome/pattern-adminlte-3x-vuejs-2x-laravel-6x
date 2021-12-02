<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\Http\Requests\AdminLTE\API\LoginPOSTRequest;

class LoginController extends Controller
{

    public function get_brand_data() {
        $objectAdminLTE = new AdminLTE();
        return $objectAdminLTE->getBrandData();
    }

    public function post(LoginPOSTRequest $request)
    {
        /* {{@snippet:begin_login_post}} */
        $adminLTEUser = AdminLTEUser::where(
                'email',
                $request->input('email'))
                ->first();

        $remember = $request->input('remember');
        auth()->guard('adminlteuser')->login($adminLTEUser, $remember);

        $objectAdminLTE = new AdminLTE();
        $landingPage = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.landingpage');

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