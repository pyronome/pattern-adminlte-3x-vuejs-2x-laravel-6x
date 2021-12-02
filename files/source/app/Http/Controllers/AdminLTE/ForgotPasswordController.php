<?php

namespace App\Http\Controllers\AdminLTE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;

class ForgotPasswordController extends Controller
{

    public $controllerName = 'forgotpassword';

    public function index(Request $request)
    {
        $viewName = 'adminlte.' . $this->controllerName;

        if (view()->exists('adminlte.custom.' . $this->controllerName))
        {
            $viewName = 'adminlte.custom.' . $this->controllerName;
        } // if (view()->exists('adminlte.custom.' . $this->controllerName))

        $viewData['controllerName'] = $this->controllerName;
        $viewData['project_title'] = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.projecttitle');
        $viewData['main_folder'] = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.mainfolder');

        return view($viewName, $viewData);
    }

}
