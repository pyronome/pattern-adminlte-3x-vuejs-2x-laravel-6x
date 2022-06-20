<?php

namespace App\Http\Controllers\Wisilo\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;
use App\Http\Requests\Wisilo\API\LoginPOSTRequest;

class LoginController extends Controller
{

    public function get_brand_data() {
        $objectWisilo = new Wisilo();
        
        return [
            'brand' => $objectWisilo->getBrandData(),
            'showregisterpage' => ('on' == $objectWisilo->getConfigParameterValue('wisilo.generalsettings.showregisterpage')),
        ];
    }

    public function post(LoginPOSTRequest $request)
    {
        /* {{@snippet:begin_login_post}} */
        $wisiloUser = WisiloUser::where(
                'email',
                $request->input('email'))
                ->first();

        $remember = $request->input('remember');
        auth()->guard('wisilouser')->login($wisiloUser, $remember);

        $objectWisilo = new Wisilo();
        $landingPage = $objectWisilo->getConfigParameterValue('wisilo.generalsettings.landingpage');

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