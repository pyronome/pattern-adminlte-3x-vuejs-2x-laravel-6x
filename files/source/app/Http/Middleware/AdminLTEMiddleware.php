<?php

namespace App\Http\Middleware;

use Closure;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;

/* {{snippet:begin_class}} */

class AdminLTEMiddleware
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

        $adminLTE = new AdminLTE();

        if (!$this->isPagePublic($request))
        {
            $adminLTEUser = auth()->guard('adminlteuser')->user();

            if (null == $adminLTEUser)
            {
                return redirect(
                        $adminLTE->getAdminLTEFolder()
                        . 'login');
            } // if (null == $adminLTEUser)
        } // if (!$this->isLogin($request)

        return $next($request);

        /* {{snippet:end_handle_method}} */
    }

    private function isPagePublic($request) {

        $adminLTEFolder = config('adminlte.main_folder');

        $publicPages = [
            'login',
            'logout',
            'forgotpassword',
	    'api/login/get_brand_data',
	    'api/login',
	    'api/forgotpassword'
        ];

        $publicPageCount = count($publicPages);
        $found = false;
        $path = $request->path();

        for ($i = 0; (($i < $publicPageCount) && !$found); $i++)
        {
            if (0 === strpos($path, ($adminLTEFolder . '/' . $publicPages[$i])))
            {
                $found = true;
            }
        }

        return $found;

    }

    /* {{snippet:end_methods}} */
}

/* {{snippet:end_class}} */
