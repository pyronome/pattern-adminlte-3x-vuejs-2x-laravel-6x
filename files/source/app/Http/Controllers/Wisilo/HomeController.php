<?php

namespace App\Http\Controllers\Wisilo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;

class HomeController extends Controller
{

    public $controllerName = 'home';

    public function index(Request $request)
    {
        $viewName = 'wisilo.' . $this->controllerName;

        if (view()->exists('wisilo.custom.' . $this->controllerName))
        {
            $viewName = 'wisilo.custom.' . $this->controllerName;
        } // if (view()->exists('wisilo.custom.' . $this->controllerName))

        $objectWisilo = new Wisilo();

        $viewData['controllerName'] = $this->controllerName;
        $viewData['user'] = $objectWisilo->getUserData();
        $viewData['customization'] = $objectWisilo->getCustomization();
        $viewData['brand'] = $objectWisilo->getBrandData();
        $viewData['project_title'] = $objectWisilo->getConfigParameterValue('wisilo.generalsettings.projecttitle');
        $viewData['main_folder'] = $objectWisilo->getConfigParameterValue('wisilo.generalsettings.mainfolder');
        $viewData['showregisterpage'] = $objectWisilo->getConfigParameterValue('wisilo.generalsettings.showregisterpage');
        $viewData['google_maps_api_key'] = $objectWisilo->getConfigParameterValue('wisilo.generalsettings.googlemapsapikey');

        // get user config val example
        // $objectWisilo->getUserConfigParameterValue($parameter, $type, $objectId);
        // $parameter: config parameter key
        // $type: group | user
        // $objectId: group == $type -> WisiloUserGroup id
        // $objectId: user == $type -> WisiloUser id
        
        return view($viewName, $viewData);
    }

}
