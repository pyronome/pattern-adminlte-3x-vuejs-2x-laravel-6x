<?php

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/* {{@snippet:begin_routes}} */

include(__DIR__ . '/wisilo-default.php');

if (file_exists(__DIR__ . '/wisilo-custom.php')) 
{
    include(__DIR__ . '/wisilo-custom.php');
}

/* {{@snippet:end_routes}} */

Route::get('{path}', 'HomeController@index')->where('path', '([A-z0-9-\/_.]+)?');