<?php

namespace App\Http\Controllers\Wisilo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Wisilo\Wisilo;

class RegisterController extends Controller
{

    public $controllerName = 'register';

    public function index(Request $request)
    {
        $objectWisilo = new Wisilo();
        $objectWisiloUser = auth()->guard('wisilouser')->user();

        if (('off' == $objectWisilo->getConfigParameterValue('wisilo.generalsettings.showregisterpage'))) {
            return redirect($objectWisilo->getWisiloFolder() . 'login');
        }

        if ($objectWisiloUser != null)
        {
            $landingPage = $objectWisilo->getConfigParameterValue('wisilo.generalsettings.landingpage');
            
            if (empty($landingPage)) {
                $landingPage = 'home';
            } // if (empty($landingPage)) {

            return redirect($objectWisilo->getWisiloFolder() . $landingPage);
        }
        else
        {

            $viewName = 'wisilo.' . $this->controllerName;

            if (view()->exists('wisilo.custom.' . $this->controllerName))
            {
                $viewName = 'wisilo.custom.' . $this->controllerName;
            } // if (view()->exists('wisilo.custom.' . $this->controllerName))

            $viewData['controllerName'] = $this->controllerName;

            $viewData['project_title'] = $objectWisilo->getConfigParameterValue('wisilo.generalsettings.projecttitle');
            $viewData['main_folder'] = $objectWisilo->getConfigParameterValue('wisilo.generalsettings.mainfolder');
            $viewData['landing_page'] = $objectWisilo->getConfigParameterValue('wisilo.generalsettings.landingpage');

            return view($viewName, $viewData);

        } // if ($objectWisiloUser != null)

    }

}