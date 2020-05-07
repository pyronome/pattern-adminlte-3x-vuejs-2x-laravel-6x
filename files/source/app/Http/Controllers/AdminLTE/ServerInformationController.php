<?php

namespace App\Http\Controllers\AdminLTE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE;
use App\AdminLTEUser;

class ServerInformationController extends Controller
{

    public $controllerName = 'server_information';

    public function index(Request $request)
    {
        $viewName = 'adminlte.' . $this->controllerName;

        if (view()->exists('adminlte.custom.' . $this->controllerName))
        {
            $viewName = 'adminlte.custom.' . $this->controllerName;
        } // if (view()->exists('adminlte.custom.' . $this->controllerName))

        $adminLTE = new AdminLTE();

        $viewData['controllerName'] = $this->controllerName;
        $viewData['user'] = $adminLTE->getUserData();

        return view($viewName, $viewData);
    }

}
