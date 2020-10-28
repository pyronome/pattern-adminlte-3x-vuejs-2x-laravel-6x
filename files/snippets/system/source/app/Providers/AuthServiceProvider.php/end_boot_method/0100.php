        Auth::shouldUse('adminlteuser');

        Gate::define('editWidget', function($user) {
            if (0 != $user->adminlteusergroup_id) {
                return $user->get_widget_permission();
            }
            
            return false;
        });