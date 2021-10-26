<?php

use App\AdminLTE\AdminLTE;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

	/* {{@snippet:begin_methods}} */

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	/* {{@snippet:begin_run_method}} */

    	$AdminLTE = new AdminLTE();
        $AdminLTE->setupAdminLTEMenu($menu);

    	/* {{@snippet:end_run_method}} */         
    }

    /* {{@snippet:end_methods}} */
}