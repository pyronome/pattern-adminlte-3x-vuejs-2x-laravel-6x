<?php

namespace App\Http\Controllers\Wisilo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Wisilo\Wisilo;

class ForgotPasswordController extends Controller
{

    public $controllerName = 'forgotpassword';

    public function index(Request $request)
    {
        $objectWisilo = new Wisilo();
        
        $viewName = 'wisilo.' . $this->controllerName;

        if (view()->exists('wisilo.custom.' . $this->controllerName))
        {
            $viewName = 'wisilo.custom.' . $this->controllerName;
        } // if (view()->exists('wisilo.custom.' . $this->controllerName))

        $viewData['controllerName'] = $this->controllerName;
        $viewData['project_title'] = $objectWisilo->getConfigParameterValue('wisilo.generalsettings.projecttitle');
        $viewData['main_folder'] = $objectWisilo->getConfigParameterValue('wisilo.generalsettings.mainfolder');

        return view($viewName, $viewData);
    }

}
