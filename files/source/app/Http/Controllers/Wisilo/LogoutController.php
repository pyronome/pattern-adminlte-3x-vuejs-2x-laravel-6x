<?php

namespace App\Http\Controllers\Wisilo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Wisilo\Wisilo;

class LogoutController extends Controller
{

    public $controllerName = 'logout';

    public function index(Request $request)
    {
        auth()->guard('wisilouser')->logout();
        $request->session()->invalidate();

        $objectWisilo = new Wisilo();
        $objectWisilo->getWisiloFolder() . 'login';
        return redirect($objectWisilo->getWisiloFolder() . 'login');
    }

}
