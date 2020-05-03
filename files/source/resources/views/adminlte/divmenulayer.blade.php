        <?php
            // Menu
            $adminLTE = new \App\AdminLTE();

            $menu = $adminLTE->getSideMenu();
            
            // Current User
            $user_data = $adminLTE->getUserData();
            $is_admin = false;
            $permissions = array();

            if ('root' == $user_data['type']) {
                $is_admin = true;
            } else {
                $permissions = $user_data['menu_permission'];
            }
        ?>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat nav-compact" data-widget="treeview" role="menu" data-accordion="false">
            	<?php
				$countMenuArray = count($menu);

				for ($i=0; $i < $countMenuArray; $i++) { 
                    $permission_token = $menu[$i]['permission_token'] . '/v';

                    if (($is_admin) || (in_array($permission_token, $permissions))) {
                        if (empty($menu[$i]['children'])) {
                            $url = $menu[$i]['url'];
                            $href = $menu[$i]['href'];
                            $title = $menu[$i]['title'];
                            $icon = $menu[$i]['icon'];
                            ?>
                            <li class="nav-item">
                                <a id="pageurl<?php echo $url; ?>" 
                                    class="nav-link"
                                    href="<?php echo ('/' . config('adminlte.main_folder') . '/' . $href); ?>" >
                                    <i class="<?php echo $icon; ?> nav-icon"></i>
                                    <p>
                                        {{ __($title) }}
                                        <!-- <span class="badge badge-info right">2</span> -->
                                    </p>
                                </a>
                            </li>
                            <?php
                        } else {
                            $parent_id = $menu[$i]['id'];
                            $parent_title = $menu[$i]['title'];
                            $parent_icon = $menu[$i]['icon'];
                            ?>
                        	<li class="nav-item has-treeview parentPageLI menu-close">
                                <a id="parentpageurl<?php echo $parent_id; ?>"
                                    class="parent_menu nav-link"
                                    href="#">
                                    <i class="<?php echo $parent_icon; ?> nav-icon"></i>
                                    <p>
                                        {{ __($parent_title) }}
                                        <i class="right fas fa-angle-left"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                	<?php
                                	$submenu = $menu[$i]['children'];
                                	$countsubmenu = count($submenu);
                                	for ($j=0; $j < $countsubmenu; $j++) {
                                        $permission_token = $submenu[$j]['permission_token'] . '/v';

                                        if (($is_admin) || (in_array($permission_token, $permissions))) {
                                            $url = $submenu[$j]['url'];
                                			$href = $submenu[$j]['href'];
                                            $title = $submenu[$j]['title'];
                                            $icon = $submenu[$j]['icon'];
                                			?>
		                                    <li class="nav-item">
		                                        <a id="pageurl<?php echo $url; ?>"
                                                    class="child_menu nav-link"
                                                    href="<?php echo ('/' . config('adminlte.main_folder') . '/' . $href); ?>"
                                                    data-parent-url="<?php echo $parent_id; ?>">
		                                            <i class="<?php echo $icon; ?> nav-icon"></i>
		                                            <p>{{ __($title) }}</p>
		                                        </a>
		                                    </li>
		                                    <?php
                                        } // if (($is_admin) || (in_array($permission_token, $permissions))) {
		                            } // for ($j=0; $j < $countsubmenu; $j++) {
		                            ?>
                                </ul>
                            </li>
                        	<?php
                        } // if (0 == count($menuArray[$i]['subMenus'])) {
                    } // if (($is_admin) || (in_array($permission_token, $permissions))) {
                    ?>
					<?php
				} // for ($i=0; $i < $countMenuArray; $i++) { 
				?>
            </ul>
        </nav>
    	<!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>