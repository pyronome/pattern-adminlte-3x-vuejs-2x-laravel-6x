<?php

namespace App\Http\Controllers\AdminLTE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;

class AdminLTEUserController extends Controller
{

    public $controllerName = 'adminlteuser';

    public function index(Request $request)
    {

        $viewName = ('adminlte.' . $this->controllerName . '_list');

        if (view()->exists('adminlte.custom.' . $this->controllerName . '_list'))
        {
            $viewName = 'adminlte.custom.' . $this->controllerName . '_list';
        } // if (view()->exists('adminlte.custom.' . $this->controllerName . '_list'))

        $objectAdminLTE = new AdminLTE();

        $viewData['controllerName'] = $this->controllerName;
        $viewData['user'] = $objectAdminLTE->getUserData();
        $viewData['customization'] = $objectAdminLTE->getCustomization();

        return view($viewName, $viewData);

    }

    public function showDetailPage(Request $request)
    {

        $viewName = ('adminlte.' . $this->controllerName . '_detail');

        if (view()->exists('adminlte.custom.' . $this->controllerName . '_detail'))
        {
            $viewName = 'adminlte.custom.' . $this->controllerName . '_detail';
        } // if (view()->exists('adminlte.custom.' . $this->controllerName . '_detail'))

        $objectAdminLTE = new AdminLTE();

        $viewData['controllerName'] = $this->controllerName;
        $viewData['user'] = $objectAdminLTE->getUserData();
        $viewData['customization'] = $objectAdminLTE->getCustomization();
        
        return view($viewName, $viewData);

    }

    public function showEditPage(Request $request)
    {

        $viewName = ('adminlte.' . $this->controllerName . '_edit');

        if (view()->exists('adminlte.custom.' . $this->controllerName . '_edit'))
        {
            $viewName = 'adminlte.custom.' . $this->controllerName . '_edit';
        } // if (view()->exists('adminlte.custom.' . $this->controllerName . '_edit'))

        $objectAdminLTE = new AdminLTE();

        $viewData['controllerName'] = $this->controllerName;
        $viewData['user'] = $objectAdminLTE->getUserData();
        $viewData['customization'] = $objectAdminLTE->getCustomization();
        
        return view($viewName, $viewData);

    }

    public function showLastUpdated(Request $request)
    {
        $objectAdminLTE = new AdminLTE();
        
        
        if ($request->session()->has(sha1('adminlteuser_lastid')))
        {
            return redirect($objectAdminLTE->getAdminLTEFolder() . $this->controllerName . '/detail/' . $request->session()->get(sha1('adminlteuser_lastid')));
        }
        else
        {
            return redirect($objectAdminLTE->getAdminLTEFolder() . $this->controllerName);
        } // if(isset($_SESSION[sha1('adminlteuser_lastid')]))

    }

}
