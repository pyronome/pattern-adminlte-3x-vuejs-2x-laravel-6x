<?php

return [
    'project_title' => env('ADMINLTE_PROJECT_TITLE', env('APP_NAME', 'AdminLTE 3.x + HTMLDB.js 1.x + Laravel 6.x')),
    'main_folder' => env('ADMINLTE_MAIN_FOLDER', 'adminlte'),
    'landing_page' => env('ADMINLTE_LANDING_PAGE', 'home'),
    'default_language' => env('ADMINLTE_DEFAULT_LANGUAGE', 'en'),
    'timezone' =>  env('ADMINLTE_TIMEZONE', 'UTC'),
    'date_format' => env('ADMINLTE_DATE_FORMAT', 'Y-m-d'),
    'time_format' => env('ADMINLTE_TIME_FORMAT', 'H:i:s'),
    'year_month_format' => env('ADMINLTE_YEAR_MONTH_FORMAT', 'Y-m'),
    'number_format' => env('ADMINLTE_NUMBER_FORMAT', ''),
    'google_maps_api_key' => env('ADMINLTE_GOOGLE_MAPS_API_KEY', ''),
];