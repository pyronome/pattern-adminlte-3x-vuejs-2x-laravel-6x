<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;

class PagePermissionController extends Controller
{

    public function get(Request $request)
    {

        $parameters = $request->route()->parameters();

        $pageName = isset($parameters['pageName'])
                ? htmlspecialchars($parameters['pageName'])
                : '';

        $response = [];

        $adminLTE = new AdminLTE();

        $userData = $adminLTE->getUserData();

        $edit_permission = $adminLTE->checkUserEditPermission(
                $pageName,
                $userData);
        
        $response['input_id'] = 'page_edit_permission';
        $response['edit_permission'] = $edit_permission ? 1 : 0;
        $response['widget_permission'] = $userData['widget_permission'];

        return $response;

    }

}
