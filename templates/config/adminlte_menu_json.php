<?php
return '[
    {
        "text": "Home",
        "href": "home",
        "icon": "fas fa-home",
        "visibility": "1"
    },
    {
        "text": "Contents",
        "href": "contents",
        "icon": "fas fa-align-justify",
        "visibility": "1",
        "children": [
{{MODEL_MENU_DEFINITONS}}
        ]
    },
    {
        "text": "Configuration",
        "href": "configuration",
        "icon": "fas fa-cog",
        "visibility": "1",
        "children": [
            {
                "text": "Server Information",
                "href": "server_information",
                "icon": "fas fa-server",
                "visibility": "1"
            },
            {
                "text": "Preferences",
                "href": "preferences",
                "icon": "fas fa-asterisk",
                "visibility": "1"
            },
            {
                "text": "General Settings",
                "href": "general_settings",
                "icon": "fas fa-atom",
                "visibility": "1"
            },
            {
                "text": "Branding",
                "href": "branding",
                "icon": "fas fa-bold",
                "visibility": "1"
            },
            {
                "text": "Mail (SMTP) Server",
                "href": "email_server",
                "icon": "fas fa-mail-bulk",
                "visibility": "1"
            },
            {
                "text": "Menu Configuration",
                "href": "menu_configuration",
                "icon": "fas fa-list-ol",
                "visibility": "1"
            },
            {
                "text": "Model Display Settings",
                "href": "adminltemodeldisplaytext",
                "icon": "fas fa-sort-alpha-down",
                "visibility": "1"
            },
            {
                "text": "Users",
                "href": "adminlteuser",
                "icon": "fas fa-user-cog",
                "visibility": "1"
            },
            {
                "text": "User Groups",
                "href": "adminlteusergroup",
                "icon": "fas fa-users-cog",
                "visibility": "1"
            }
        ]
    },
    {
        "text": "Logout",
        "href": "logout",
        "icon": "fas fa-power-off",
        "visibility": "1"
    }
]';
?>