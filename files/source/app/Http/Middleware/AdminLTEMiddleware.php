<?php

namespace App\Http\Middleware;

use Closure;
use App\AdminLTE;
use App\AdminLTEUser;

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