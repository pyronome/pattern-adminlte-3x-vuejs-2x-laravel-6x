<?php

namespace App\Http\Middleware;

use Closure;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;

/* {{@snippet:begin_class}} */

class WisiloMiddleware
{

	/* {{@snippet:begin_properties}} */

	/* {{@snippet:end_properties}} */

	/* {{@snippet:begin_methods}} */

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /* {{@snippet:begin_handle_method}} */

        $wisilo = new Wisilo();

        if (!$this->isPagePublic($request, $wisilo->getConfigParameterValue('wisilo.generalsettings.mainfolder')))
        {
            $wisiloUser = auth()->guard('wisilouser')->user();

            if (null == $wisiloUser)
            {
                return redirect(
                        $wisilo->getWisiloFolder()
                        . 'login');
            } // if (null == $wisiloUser)
        } // if (!$this->isLogin($request)

        return $next($request);

        /* {{@snippet:end_handle_method}} */
    }

    private function isPagePublic($request, $wisiloFolder) {

        $wisiloFolder = config('wisilo.main_folder');

        $publicPages = [
            'register',
            'login',
            'logout',
            'forgotpassword',
            'api/register/get_brand_data',
            'api/register',
            'api/login/get_brand_data',
            'api/login',
            'api/forgotpassword'
        ];

        $publicPageCount = count($publicPages);
        $found = false;
        $path = $request->path();

        for ($i = 0; (($i < $publicPageCount) && !$found); $i++)
        {
            if (0 === strpos($path, ($wisiloFolder . '/' . $publicPages[$i])))
            {
                $found = true;
            }
        }

        return $found;

    }

    /* {{@snippet:end_methods}} */
}

/* {{@snippet:end_class}} */
