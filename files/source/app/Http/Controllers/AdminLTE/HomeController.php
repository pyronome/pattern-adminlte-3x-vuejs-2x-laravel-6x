<?php

namespace App\Http\Controllers\AdminLTE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;

class HomeController extends Controller
{

    public $controllerName = 'home';

    public function index(Request $request)
    {
        $viewName = 'adminlte.' . $this->controllerName;

        if (view()->exists('adminlte.custom.' . $this->controllerName))
        {
            $viewName = 'adminlte.custom.' . $this->controllerName;
        } // if (view()->exists('adminlte.custom.' . $this->controllerName))

        $objectAdminLTE = new AdminLTE();

        $viewData['controllerName'] = $this->controllerName;
        $viewData['user'] = $objectAdminLTE->getUserData();
        $viewData['customization'] = $objectAdminLTE->getCustomization();
        $viewData['brand'] = $objectAdminLTE->getBrandData();
        $viewData['project_title'] = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.projecttitle');
        $viewData['main_folder'] = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.mainfolder');
        $viewData['google_maps_api_key'] = $objectAdminLTE->getConfigParameterValue('adminlte.generalsettings.googlemapsapikey');
        
        return view($viewName, $viewData);
    }

}
