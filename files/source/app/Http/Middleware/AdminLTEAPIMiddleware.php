<?php

namespace App\Http\Middleware;

use Closure;
use App\AdminLTE;
use App\AdminLTEUser;
use App\HTMLDB;

/* {{snippet:begin_class}} */

class AdminLTEHTMLDBMiddleware
{

	/* {{snippet:begin_properties}} */

	/* {{snippet:end_properties}} */

	/* {{snippet:begin_methods}} */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* {{snippet:begin_handle_method}} */

        $permissionResult = [];

        if ($request->isMethod('delete'))
        {
            $permissionResult = $this->checkUserDeletePermission($request);
        }
        else if ($request->isMethod('post'))
        {
            $permissionResult = $this->checkUserPostPermission($request);
        }
        else
        {
            $permissionResult = $this->checkUserGetPermission($request);
        } // if ($request->isMethod('post'))s

        if ($permissionResult['error'])
        {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->errorCount = 1;
            $objectHTMLDB->lastError = $permissionResult['error_msg'];
            $objectHTMLDB->printResponseJSON();
            return;
        } // if ($permissionResult['error'])

        return $next($request);

        /* {{snippet:end_handle_method}} */
    }

    public function checkUserGetPermission($request)
    {

        $adminLTE = new AdminLTE();

        $directoryName = dirname($request->path());
        $fileName = basename($request->path());

        $result = array();
        $result['error'] = false;
        $result['error_msg'] = __('You do not have READ permissions for <b>"'
                . $directoryName
                . '/'
                . $fileName
                . '"</b> service.<br>Contact your system administrator'
                . ' for more information.');

        if ($this->isPagePublic($request))
        {
            // Public Page
            $result['error'] = false;
            $result['error_msg'] = '';
            return $result;
        } // if ($this->isPagePublic($request))

        $user_data = $adminLTE->getUserData();

        if (0 == $user_data['id'])
        {
            $result['error'] = true;
            return $result;
        } // if (0 == $user_data['id'])

        if (1 == $user_data['id'])
        {
            // Root User
            $result['error'] = false;
            $result['error_msg'] = '';
            return $result;
        } // if (1 == $user_data['id'])
	
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $extension = '.' . $extension;
        $fileName = str_replace($extension, '', $fileName);

        $permission_token = $directoryName . '/' . $fileName . '/g';

	    $permissions = $user_data['service_permission'];

        if (!in_array($permission_token, $permissions))
        {
            $result['error'] = true;
        } // if (!in_array($permission_token, $permissions))

        return $result;

    }

    public function checkUserPostPermission($request)
    {

        $adminLTE = new AdminLTE();

        $directoryName = dirname($request->path());
        $fileName = basename($request->path());

        $result = array();
        $result['error'] = false;
        $result['error_msg'] = __('You do not have WRITE permissions for <b>"'
                . $directoryName
                . '/'
                . $fileName
                . '"</b> service.<br>Contact your system administrator'
                . ' for more information.');

        if ($this->isPagePublic($request))
        {
            // Public Page
            $result['error'] = false;
            $result['error_msg'] = '';
            return $result;
        } // if ($this->isPagePublic($request))

        $user_data = $adminLTE->getUserData();

        if (0 == $user_data['id'])
        {
            $result['error'] = true;
            return $result;
        } // if (0 == $user_data['id'])

        if (1 == $user_data['id'])
        {
            // Root User
            $result['error'] = false;
            $result['error_msg'] = '';
            return $result;
        } // if (1 == $user_data['id'])
        
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $extension = '.' . $extension;
        $fileName = str_replace($extension, '', $fileName);

        $permission_token = $directoryName . '/' . $fileName . '/p';

        $permissions = $user_data['service_permission'];

        if (!in_array($permission_token, $permissions))
        {
            $result['error'] = true;
        } // if (!in_array($permission_token, $permissions))

        return $result;

    }

    public function checkUserDeletePermission($request)
    {

        $adminLTE = new AdminLTE();

        $directoryName = dirname($request->path());
        $fileName = basename($request->path());

        $result = array();
        $result['error'] = false;
        $result['error_msg'] = __('You do not have DELETE permissions for <b>"'
                . $directoryName
                . '/'
                . $fileName
                . '"</b> service.<br>Contact your system administrator'
                . ' for more information.');

        if ($this->isPagePublic($request))
        {
            // Public Page
            $result['error'] = false;
            $result['error_msg'] = '';
            return $result;
        } // if ($this->isPagePublic($request))

        $user_data = $adminLTE->getUserData();

        if (0 == $user_data['id'])
        {
            $result['error'] = true;
            return $result;
        } // if (0 == $user_data['id'])

        if (1 == $user_data['id'])
        {
            // Root User
            $result['error'] = false;
            $result['error_msg'] = '';
            return $result;
        } // if (1 == $user_data['id'])
        
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        $extension = '.' . $extension;
        $fileName = str_replace($extension, '', $fileName);

        $permission_token = $directoryName . '/' . $fileName . '/d';

        $permissions = $user_data['service_permission'];

        if (!in_array($permission_token, $permissions))
        {
            $result['error'] = true;
        } // if (!in_array($permission_token, $permissions))

        return $result;

    }

    private function isPagePublic($request)
    {

        $publicPages = [
            '/login',
            '/logout',
            '/forgotpassword'
        ];

        if (preg_match('(' . implode('|', $publicPages) . ')', $request->path()) === 1)
        {
            return true;
        } else {
            return false;
        } // if (preg_match('(' . implode('|', $publicPages) . ')', $request->path()) === 1)

    }

    /* {{snippet:end_methods}} */
}

/* {{snippet:end_class}} */