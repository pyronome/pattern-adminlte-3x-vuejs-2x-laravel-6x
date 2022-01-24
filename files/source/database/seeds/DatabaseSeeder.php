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

        $this->updateAdminLTEConfigParameters();
        $this->updateAdminLTEUserConfigParameters();
        $this->updateAdminLTEModelMenu();
        $this->updateAdminLTEPluginMenu();
        $this->updateLayout();

    	/* {{@snippet:end_run_method}} */         
    }

    public function updateAdminLTEConfigParameters() {
        /* {{@snippet:begin_update_config_parameters_method}} */

        require(__DIR__ . '/ConfigParameters.php');
        $adminLTE = new AdminLTE();
        $adminLTE->updateAdminLTEConfig($config);

        /* {{@snippet:end_update_config_parameters_method}} */
    }

    public function updateAdminLTEUserConfigParameters() {
        /* {{@snippet:begin_update_user_config_parameters_method}} */

        require(__DIR__ . '/UserConfigParameters.php');
        $adminLTE = new AdminLTE();
        $adminLTE->updateAdminLTEUserConfig($config);

        /* {{@snippet:end_update_user_config_parameters_method}} */
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

    public function updateLayout() {
		$AdminLTE = new AdminLTE();
		$AdminLTE->setAdminLTEDefaultLayout();
    }

    /* {{@snippet:end_methods}} */
}
