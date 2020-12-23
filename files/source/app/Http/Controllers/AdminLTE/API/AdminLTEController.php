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
        
        // Permissions
        $permissions = $objectAdminLTE->getUserPermissionData();

        return [
            'is_admin' => $admin,
            'show_widget_config_button' => $show_widget_config_button,
            'permissions' => $permissions
        ];
    }

    public function get_permission_form(Request $request)
    {   
        $parameters = $request->route()->parameters();

        $objectAdminLTE = new AdminLTE();

        // menu permission items
        $menu = $objectAdminLTE->getSideMenu();
        $menu_permission_items = [];
        $item_index = 0;

        $countMenuArray = count($menu);

        for ($i=0; $i < $countMenuArray; $i++) {
            $menu_permission_items[$item_index]['value'] = $menu[$i]['href'];
            $menu_permission_items[$item_index]['title'] = $menu[$i]['title'];
            $item_index++;

            $submenu = $menu[$i]['children'];
            $countsubmenu = count($submenu);
            for ($j=0; $j < $countsubmenu; $j++) {
                $menu_permission_items[$item_index]['value'] = $submenu[$j]['href'];
                $menu_permission_items[$item_index]['title'] = $submenu[$j]['title'];
                $item_index++;
            }
        }

        // other api permission items
        $other_permission_items = [];
        $index = 0;

        // read api config from DB or read with snippet
        // example
        /* $other_permission_items[$index]['meta_key'] = '__google_api';
        $other_permission_items[$index]['title'] = 'Google API';
        $item_index = 0;
        $other_permission_items[$index]['items'][$item_index]['value'] = 'captcha';
        $other_permission_items[$index]['items'][$item_index]['title'] = 'G-Captcha';
        $item_index++;
        $other_permission_items[$index]['items'][$item_index]['value'] = 'map';
        $other_permission_items[$index]['items'][$item_index]['title'] = 'Maps';
        $item_index++;
        $other_permission_items[$index]['items'][$item_index]['value'] = 'sign_in';
        $other_permission_items[$index]['items'][$item_index]['title'] = 'Sign-In'; */

        /* {{snippet:other_permissions}} */

        return [
            'menu_permission_items' => $menu_permission_items,
            'model_permission_items' => $objectAdminLTE->getModelList(),
            'other_permission_items' => $other_permission_items
        ];
    }
}