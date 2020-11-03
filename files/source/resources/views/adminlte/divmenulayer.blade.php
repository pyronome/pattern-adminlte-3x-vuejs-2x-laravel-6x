        <?php
            $adminLTE = new \App\AdminLTE\AdminLTE();
            $menu = $adminLTE->getSideMenu();
        ?>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ $customization['nav-sidebar'] }}" data-widget="treeview" role="menu" data-accordion="false">
                <?php
                $countMenuArray = count($menu);
                $logoutMenu = null;

                for ($i=0; $i < $countMenuArray; $i++) { 
                    if (empty($menu[$i]['children'])) {
                        $url = $menu[$i]['url'];
                        $href = $menu[$i]['href'];
                        $title = $menu[$i]['title'];
                        $icon = $menu[$i]['icon'];

                        if ($href == 'logout') {
                            $logoutMenu = $menu[$i];
                            continue;
                        } // if ($href == 'logout') {
                        ?>
                        <li class="nav-item" data-href="<?php echo $href; ?>">
                            <router-link id="pageurl<?php echo $url; ?>"
                                class="nav-link"
                                to="<?php echo ('/' . config('adminlte.main_folder') . '/' . $href); ?>" >
                                <i class="<?php echo $icon; ?> nav-icon"></i>
                                <p>
                                    {{ __($title) }}
                                    <!-- <span class="badge badge-info right">2</span> -->
                                </p>
                            </router-link>
                        </li>
                        <?php
                    } else {
                        $parent_id = $menu[$i]['id'];
                        $parent_title = $menu[$i]['title'];
                        $parent_icon = $menu[$i]['icon'];
                        $parent_href = $menu[$i]['href'];
                        ?>
                        <li class="nav-item has-treeview parentPageLI menu-close" data-href="<?php echo $parent_href; ?>">
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
                                    $url = $submenu[$j]['url'];
                                    $href = $submenu[$j]['href'];
                                    $title = $submenu[$j]['title'];
                                    $icon = $submenu[$j]['icon'];
                                    ?>
                                    <li class="nav-item" data-href="<?php echo $href; ?>">
                                        <router-link id="pageurl<?php echo $url; ?>"
                                            class="child_menu nav-link"
                                            to="<?php echo ('/' . config('adminlte.main_folder') . '/' . $href); ?>"
                                            data-parent-url="<?php echo $parent_id; ?>">
                                            <i class="<?php echo $icon; ?> nav-icon"></i>
                                            <p>{{ __($title) }}</p>
                                        </router-link>
                                    </li>
                                    <?php
                                } // for ($j=0; $j < $countsubmenu; $j++) {
                                ?>
                            </ul>
                        </li>
                        <?php
                    } // if (empty($menu[$i]['children'])) {
                    ?>
                    <?php
                } // for ($i=0; $i < $countMenuArray; $i++) { 
                ?>
                <?php
                if ($logoutMenu != null) {
                    $url = $logoutMenu['url'];
                    $href = $logoutMenu['href'];
                    $title = $logoutMenu['title'];
                    $icon = $logoutMenu['icon'];
                ?>
                <li class="nav-item"data-href="logout">
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
                } // if ($logoutMenu != null) {
                ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>