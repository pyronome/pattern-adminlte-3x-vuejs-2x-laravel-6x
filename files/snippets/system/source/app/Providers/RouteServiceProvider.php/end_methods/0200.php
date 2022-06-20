
    /**
     * Define the "wisilo" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWisiloRoutes()
    {
        $wisiloFolder = config('wisilo.main_folder');
        Route::prefix($wisiloFolder)
                ->middleware(['web', WisiloMiddleware::class])
                ->namespace('App\Http\Controllers\Wisilo')
                ->group(base_path('routes/wisilo.php'));
    }
