<?php

namespace App\Http\Controllers\AdminLTE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;

class LoginController extends Controller
{

    public $controllerName = 'login';

    public function index(Request $request)
    {

        $objectAdminLTE = new AdminLTE();
        $objectAdminLTEUser = auth()->guard('adminlteuser')->user();

        if ($objectAdminLTEUser != null)
        {
            return redirect($objectAdminLTE->getAdminLTEFolder() . 'home');
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

        } // if ($objectAdminLTEUser != null)

    }

}
