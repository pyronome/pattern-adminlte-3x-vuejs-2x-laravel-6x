        Auth::shouldUse('wisilouser');
		
		Gate::define('isAdmin', function($user) {
            if (0 != $user->wisilousergroup_id) {
                return $user->is_admin();
            }
            
            return false;
        });

        Gate::guessPolicyNamesUsing(function ($modelClass) {
            return Str::startsWith($modelClass, 'App\Wisilo')
                ? 'App\Policies\\' . str_replace('App\Wisilo\\', '', $modelClass) . 'Policy'
                : 'App\Policies\\' . class_basename($modelClass) . 'Policy';
        });