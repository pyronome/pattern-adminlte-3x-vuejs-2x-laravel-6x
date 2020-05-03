<?php

namespace App\Http\Controllers\AdminLTE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE;

class LoginController extends Controller
{

    public $controllerName = 'login';

    public function index(Request $request)
    {

        $adminLTE = new AdminLTE();
        $adminLTEUser = auth()->guard('adminlteuser')->user();

        if ($adminLTEUser != null)
        {
            return redirect($adminLTE->getAdminLTEFolder() . 'home');
        }
        else
        {

            $viewName = 'adminlte.' . $this->controllerName;

            if (view()->exists('adminlte.custom.' . $this->controllerName))
            {
                $viewName = 'adminlte.custom.' . $this->controllerName;
            } // if (view()->exists('adminlte.custom.' . $this->controllerName))

            $viewData['controllerName'] = $this->controllerName;

            return view($viewName, $viewData);

        } // if ($adminLTEUser != null)

    }

}
