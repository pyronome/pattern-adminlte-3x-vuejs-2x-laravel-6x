
    /**
     * Define the "adminlte" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAdminLTERoutes()
    {
        $adminLTEFolder = config('adminlte.main_folder');
        Route::prefix($adminLTEFolder)
             ->middleware(['web', AdminLTEMiddleware::class])
             ->namespace('App\Http\Controllers\AdminLTE')
             ->group(base_path('routes/adminlte.php'));
    }
