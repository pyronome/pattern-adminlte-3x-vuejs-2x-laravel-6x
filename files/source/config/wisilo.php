<?php

return [
    'project_title' => env('WISILO_PROJECT_TITLE', 'Wisilo'),
    'main_folder' => env('WISILO_MAIN_FOLDER', 'wisilo'),
    'landing_page' => env('WISILO_LANDING_PAGE', 'home'),
    'default_language' => env('WISILO_DEFAULT_LANGUAGE', 'en'),
    'timezone' =>  env('WISILO_TIMEZONE', 'UTC'),
    'date_format' => env('WISILO_DATE_FORMAT', 'Y-m-d'),
    'time_format' => env('WISILO_TIME_FORMAT', 'H:i:s'),
    'year_month_format' => env('WISILO_YEAR_MONTH_FORMAT', 'Y-m'),
    'number_format' => env('WISILO_NUMBER_FORMAT', ''),
    'google_maps_api_key' => env('WISILO_GOOGLE_MAPS_API_KEY', ''),
];