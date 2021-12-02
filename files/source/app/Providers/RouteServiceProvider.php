<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use App\AdminLTE\AdminLTE;
use App\Http\Middleware\AdminLTEMiddleware;



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


        $this->mapAdminLTERoutes();

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
     * Define the "adminlte" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAdminLTERoutes()
    {
        $adminLTEFolder = 'adminlte';
        if (Schema::hasTable('adminlteconfigtable')) {
            $adminLTE = new AdminLTE();
            $adminLTEFolder =  $adminLTE->getConfigParameterValue('adminlte.generalsettings.mainfolder');
        }

        Route::prefix($adminLTEFolder)
                ->middleware(['web', AdminLTEMiddleware::class])
                ->namespace('App\Http\Controllers\AdminLTE')
                ->group(base_path('routes/adminlte.php'));
    }

}

/* {{@snippet:end_class}} */
