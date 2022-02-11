<?php

namespace App\Http\Controllers\AdminLTE\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
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

        $objectAdminLTE = new AdminLTE();

        // widget edit button
        $show_widget_config_button = false;
        if (Gate::allows('editWidget')) {
            $show_widget_config_button = true;        
        }

        // is user admin ?
        $admin = false;
        if (Gate::allows('isAdmin')) {
            $admin = true;
        }

        // widgets
        $active_widgets = $objectAdminLTE->getUserWidgets($componentName);
        
        // Permissions
        $menu_permissions = $objectAdminLTE->getUserMenuPermissions();
        $model_permissions = $objectAdminLTE->getUserModelPermissions();

        return [
            'is_admin' => $admin,
            'show_widget_config_button' => $show_widget_config_button,
            'menu_permissions' => $menu_permissions,
            'model_permissions' => $model_permissions,
            'active_widgets' => $active_widgets
        ];
    }
}
