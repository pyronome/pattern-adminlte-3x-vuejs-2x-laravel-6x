        Auth::shouldUse('adminlteuser');
		
		Gate::define('isAdmin', function($user) {
            if (0 != $user->adminlteusergroup_id) {
                return $user->is_admin();
            }
            
            return false;
        });

        Gate::guessPolicyNamesUsing(function ($modelClass) {
            return Str::startsWith($modelClass, 'App\AdminLTE')
                ? 'App\Policies\\' . str_replace('App\AdminLTE\\', '', $modelClass) . 'Policy'
                : 'App\Policies\\' . class_basename($modelClass) . 'Policy';
        });