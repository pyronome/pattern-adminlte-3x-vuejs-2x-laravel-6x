<?php

use App\Wisilo\Wisilo;
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

        $this->updateWisiloConfigParameters();
        $this->updateWisiloUserConfigParameters();
        $this->updateWisiloModelMenu();
        $this->updateWisiloPluginMenu();
        $this->updateLayout();
        $this->updateCustomVariables();

    	/* {{@snippet:end_run_method}} */         
    }

    public function updateWisiloConfigParameters() {
        /* {{@snippet:begin_update_config_parameters_method}} */

        require(__DIR__ . '/ConfigParameters.php');
        $wisilo = new Wisilo();
        $wisilo->updateWisiloConfig($config);

        /* {{@snippet:end_update_config_parameters_method}} */
    }

    public function updateWisiloUserConfigParameters() {
        /* {{@snippet:begin_update_user_config_parameters_method}} */

        require(__DIR__ . '/UserConfigParameters.php');
        $wisilo = new Wisilo();
        $wisilo->updateWisiloUserConfig($config);

        /* {{@snippet:end_update_user_config_parameters_method}} */
    }

    public function updateWisiloModelMenu() {
        $menu = [];

        /* {{@snippet:model_menu_definitions}} */ 

        $wisilo = new Wisilo();
        $wisilo->updateWisiloMenu($menu);
    }

    public function updateWisiloPluginMenu() {
        $menu = [];

        /* {{@snippet:begin_plugin_menu_definitions}} */
        
        /* {{@snippet:end_plugin_menu_definitions}} */
    }

    public function updateLayout() {
		$Wisilo = new Wisilo();
		$Wisilo->setWisiloDefaultLayout();
    }

    public function updateCustomVariables() {
		$Wisilo = new Wisilo();
		$Wisilo->setWisiloCustomVariables();
    }

    /* {{@snippet:end_methods}} */
}
