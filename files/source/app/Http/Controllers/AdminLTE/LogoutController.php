<?php

namespace App\Http\Controllers\AdminLTE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE;

class LogoutController extends Controller
{

    public $controllerName = 'logout';

    public function index(Request $request)
    {
        auth()->guard('adminlteuser')->logout();
        $request->session()->invalidate();

        $adminLTE = new AdminLTE();
        $adminLTE->getAdminLTEFolder() . 'login';
        return redirect($adminLTE->getAdminLTEFolder() . 'login');
    }

}
