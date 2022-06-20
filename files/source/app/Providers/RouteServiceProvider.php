<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\Wisilo\Wisilo;
use App\Http\Middleware\WisiloMiddleware;



class RouteServiceProvider extends ServiceProvider
{

    /* {{@snippet:begin_methods}} */

    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {

        /* {{@snippet:begin_map_method}} */

        $this->mapApiRoutes();

        $this->mapWebRoutes();


        $this->mapWisiloRoutes();

    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }


    /**
     * Define the "wisilo" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWisiloRoutes()
    {
        $wisiloFolder = 'wisilo';
        if (Schema::hasTable('wisiloconfigtable')) {
            $wisilo = new Wisilo();
            $wisiloFolder =  $wisilo->getConfigParameterValue('wisilo.generalsettings.mainfolder');
        }

        Route::prefix($wisiloFolder)
                ->middleware(['web', WisiloMiddleware::class])
                ->namespace('App\Http\Controllers\Wisilo')
                ->group(base_path('routes/wisilo.php'));
    }

}

/* {{@snippet:end_class}} */
