<?php

namespace App\Http\Controllers\AdminLTE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;

class LogoutController extends Controller
{

    public $controllerName = 'logout';

    public function index(Request $request)
    {
        auth()->guard('adminlteuser')->logout();
        $request->session()->invalidate();

        $objectAdminLTE = new AdminLTE();
        $objectAdminLTE->getAdminLTEFolder() . 'login';
        return redirect($objectAdminLTE->getAdminLTEFolder() . 'login');
    }

}
