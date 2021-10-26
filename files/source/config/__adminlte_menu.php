<?php

$__adminlte_menu = [];

$menu_item = [];
$menu_item['text'] = 'Home';
$menu_item['href'] = 'home';
$menu_item['icon'] = 'fas fa-home';
$menu_item['visibility'] = 1;
$menu_item['parent'] = '';
array_push($__adminlte_menu, $menu_item);

$menu_item = [];
$menu_item['text'] = 'Contents';
$menu_item['href'] = 'contents';
$menu_item['icon'] = 'fas fa-align-justify';
$menu_item['visibility'] = 1;
$menu_item['parent'] = '';
array_push($__adminlte_menu, $menu_item);

$menu_item = [];
$menu_item['text'] = 'Configuration';
$menu_item['href'] = 'configuration';
$menu_item['icon'] = 'fas fa-cog';
$menu_item['visibility'] = 1;
$menu_item['parent'] = '';
array_push($__adminlte_menu, $menu_item);

$menu_item = [];
$menu_item['text'] = 'Server Information';
$menu_item['href'] = 'server_information';
$menu_item['icon'] = 'fas fa-server';
$menu_item['visibility'] = 1;
$menu_item['parent'] = 'configuration';
array_push($__adminlte_menu, $menu_item);

$menu_item = [];
$menu_item['text'] = 'Preferences';
$menu_item['href'] = 'preferences';
$menu_item['icon'] = 'fas fa-asterisk';
$menu_item['visibility'] = 1;
array_push($__adminlte_menu, $menu_item);
    
$menu_item = [];
$menu_item['text'] = 'General Settings';
$menu_item['href'] = 'general_settings';
$menu_item['icon'] = 'fas fa-atom';
$menu_item['visibility'] = 1;
array_push($__adminlte_menu, $menu_item);
    
$menu_item = [];
$menu_item['text'] = 'Branding';
$menu_item['href'] = 'branding';
$menu_item['icon'] = 'fas fa-bold';
$menu_item['visibility'] = 1;
array_push($__adminlte_menu, $menu_item);
    
$menu_item = [];
$menu_item['text'] = 'Mail (SMTP) Server';
$menu_item['href'] = 'email_server';
$menu_item['icon'] = 'fas fa-mail-bulk';
$menu_item['visibility'] = 1;
array_push($__adminlte_menu, $menu_item);
    
$menu_item = [];
$menu_item['text'] = 'Menu Configuration';
$menu_item['href'] = 'menu_configuration';
$menu_item['icon'] = 'fas fa-list-ol';
$menu_item['visibility'] = 1;
array_push($__adminlte_menu, $menu_item);
    
$menu_item = [];
$menu_item['text'] = 'Model Display Settings';
$menu_item['href'] = 'adminltemodeldisplaytext';
$menu_item['icon'] = 'fas fa-sort-alpha-down';
$menu_item['visibility'] = 1;
array_push($__adminlte_menu, $menu_item);
    
$menu_item = [];
$menu_item['text'] = 'Users';
$menu_item['href'] = 'adminlteuser';
$menu_item['icon'] = 'fas fa-user-cog';
$menu_item['visibility'] = 1;
array_push($__adminlte_menu, $menu_item);
    
$menu_item = [];
$menu_item['text'] = 'User Groups';
$menu_item['href'] = 'adminlteusergroup';
$menu_item['icon'] = 'fas fa-users-cog';
$menu_item['visibility'] = 1;
array_push($__adminlte_menu, $menu_item);

$menu_item = [];
$menu_item['text'] = 'Logout';
$menu_item['href'] = 'logout';
$menu_item['icon'] = 'fas fa-power-off';
$menu_item['visibility'] = 1;
$menu_item['parent'] = '';
array_push($__adminlte_menu, $menu_item);

return $__adminlte_menu;
?>