<?php
            $wisilo = new \App\Wisilo\Wisilo();
            $menu = $wisilo->getSideMenu();
            $main_folder = $wisilo->getConfigParameterValue('wisilo.generalsettings.mainfolder');
        ?>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ $customization['nav-sidebar'] }}" data-widget="treeview" role="menu" data-accordion="false" style="padding-bottom:60px;">
                <?php
                $countMenuArray = count($menu);
                $logoutMenu = null;

                for ($i=0; $i < $countMenuArray; $i++) { 
                    if (1 == $menu[$i]['__group']) {
                        $title = $menu[$i]['title'];
                        ?>
                        <li class="nav-header menu-nav-item" data-href="__no_href__" style="padding: 0.75rem 1rem 1rem;">
                            <span class="" id="pageurl__no_href__">
                                {{ __($title) }}
                            </span>
                        </li>
                        <?php
                    } else {
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
                            <li class="nav-item menu-nav-item" data-href="<?php echo $href; ?>" style="display:none;">
                                <router-link id="pageurl<?php echo $url; ?>"
                                    class="nav-link"
                                    to="<?php echo ('/' . $main_folder . '/' . $href); ?>" >
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
                            <li class="nav-item menu-nav-item has-treeview parentPageLI menu-close" data-href="<?php echo $parent_href; ?>" style="display:none;">
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
                                        <li class="nav-item menu-nav-item" data-href="<?php echo $href; ?>" style="display:none;">
                                            <router-link id="pageurl<?php echo $url; ?>"
                                                class="child_menu nav-link"
                                                to="<?php echo ('/' . $main_folder . '/' . $href); ?>"
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
                    } // if (1 == $menu[$i]['__group']) {
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
                <li id="logout-menu" class="nav-item menu-nav-item" data-href="logout" style="display:none;">
                    <a id="pageurl<?php echo $url; ?>" 
                        class="nav-link"
                        href="<?php echo ('/' . $main_folder . '/' . $href); ?>" >
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