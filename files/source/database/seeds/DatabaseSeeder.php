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
        $this->updateAdminLTEModelMenu();
        $this->updateAdminLTEPluginMenu();
        $this->updateLayout();

		$this->seedModelOptions();

    }

    public function updateAdminLTEConfigParameters() {
        require_once(__DIR__ . '\ConfigParameters.php');
        $adminLTE = new AdminLTE();
        $adminLTE->updateAdminLTEConfig($config);
    }

    public function updateAdminLTEModelMenu() {
        $menu = [];

		$menu[] = [
			'text' => 'Student List',
			'href' => 'student',
			'icon' => 'far fa-list-alt',
			'visibility' => 1,
			'parent' => 'contents'
        ];

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

	public function seedModelOptions() {
		$AdminLTE = new AdminLTE();
		$option_data_list = [];


		
		$AdminLTE->seedModelDropdownOptions($option_data_list);
    }
}