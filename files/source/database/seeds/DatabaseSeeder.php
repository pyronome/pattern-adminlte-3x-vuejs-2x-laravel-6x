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

        $this->updateAdminLTEModelMenu();
        $this->updateAdminLTEPluginMenu();

    	/* {{@snippet:end_run_method}} */         
    }

    public function updateAdminLTEModelMenu() {
        $menu = [];

        /* {{@snippet:model_menu_definitions}} */ 

        $adminLTE = new AdminLTE();
        $adminLTE->updateAdminLTEMenu($menu);
    }

    public function updateAdminLTEPluginMenu() {
        $menu = [];

        /* {{@snippet:begin_plugin_menu_definitions}} */ 
        
        /* {{@snippet:end_plugin_menu_definitions}} */
    }

    /* {{@snippet:end_methods}} */
}