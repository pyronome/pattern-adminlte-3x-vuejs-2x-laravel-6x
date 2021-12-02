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
            $landingPage = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.landingpage');
            
            if (empty($landingPage)) {
                $landingPage = 'home';
            } // if (empty($landingPage)) {

            return redirect($objectAdminLTE->getAdminLTEFolder() . $landingPage);
        }
        else
        {

            $viewName = 'adminlte.' . $this->controllerName;

            if (view()->exists('adminlte.custom.' . $this->controllerName))
            {
                $viewName = 'adminlte.custom.' . $this->controllerName;
            } // if (view()->exists('adminlte.custom.' . $this->controllerName))

            $viewData['controllerName'] = $this->controllerName;

            $viewData['project_title'] = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.projecttitle');
            $viewData['main_folder'] = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.mainfolder');
            $viewData['landing_page'] = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.landingpage');

            return view($viewName, $viewData);

        } // if ($objectAdminLTEUser != null)

    }

}