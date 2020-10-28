<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\AdminLTE\AdminLTEUserGroup;

class AdminLTEController extends Controller
{

    public function get_page_variables(Request $request)
    {   
        $parameters = $request->route()->parameters();

        $componentName = isset($parameters['componentName'])
                ? htmlspecialchars($parameters['componentName'])
                : '';
                 
        $show_widget_config_button = false;
        $exceptions = ['AdminLTEModelDisplayText', 'Branding', 'EmailServer', 'GeneralSettings', 'MenuConfiguration', 'Preferences', 'ServerInformation'];
    
        if (!in_array($componentName, $exceptions)) {
            if (Gate::allows('editWidget')) {
                $show_widget_config_button = true;        
            }
        }

        return [
            'show_widget_config_button' => $show_widget_config_button
        ];
    }
}