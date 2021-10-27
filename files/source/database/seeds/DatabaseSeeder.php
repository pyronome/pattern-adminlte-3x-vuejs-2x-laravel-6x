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

        $this->setupAdminLTEModelMenu();

    	/* {{@snippet:end_run_method}} */         
    }

    public function setupAdminLTEModelMenu() {
        $menu = [];

        /* {{@snippet:model_menu_definitions}} */ 

        $adminLTE = new AdminLTE();
        $adminLTE->updateAdminLTEMenu($menu);
    }

    /* {{@snippet:end_methods}} */
}