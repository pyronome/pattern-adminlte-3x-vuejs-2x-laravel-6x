<?php

namespace App\Http\Controllers\Wisilo\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;

class WisiloServiceController extends Controller
{

    public $exceptions = [
        'WisiloLayoutController.php',
        'PagePermissionController.php',
        'WisiloServiceController.php',
        'WisiloWidgetController.php',
        'ForgotPasswordController.php',
        'LoginController.php',
        'ProfileController.php'
    ];

    public function get(Request $request)
    {

        $list = array();

        $Services = array();
        $index = 0;

        $path = (app_path() . '/Http/Controllers/Wisilo/API/');
        if (is_dir($path)) {
            $files = scandir($path);
            foreach ($files as $file) {
                if (($file != ".") && ($file != "..")) {
                    $current_path = ($path . "/" . $file);
                    if (!in_array($file, $this->exceptions)) {
                        if (is_file($current_path)) {
                            $extension = pathinfo($file, PATHINFO_EXTENSION);
                            $extension = '.' . $extension;
                            $file_name = str_replace($extension, '', $file);
                            $Services[$index]['directory_name'] = '';
                            $Services[$index]['file_name'] = $file_name;
                            $index++;
                        } // if (is_file($current_path)) {
                    } // if (!in_array($file, $exceptions)) {
                } // if (($file != ".") && ($file != "..")) {
            } // foreach ($files as $file) {
        } // if (is_dir($path)) {

        $countServices = count($Services);
        $index = 0;

        for ($i=0; $i < $countServices; $i++) { 
            $Service = $Services[$i];

            $list[$index]['id'] = ($index + 1);
            $list[$index]['title'] = $Service['directory_name'] . '/' . $Service['file_name'];
            $list[$index]['directory_name'] = $Service['directory_name'];
            $list[$index]['file_name'] = $Service['file_name'];

            $index++;
        } // for ($i=0; $i < $countServices; $i++) { 

        return $list;

    }

    public function get_directories(Request $request)
    {

        $list = [];
        $Directories = [];

        $countDirectories = count($Directories);
        $index = 0;

        for ($i=0; $i < $countDirectories; $i++) { 
            $list[$index]['id'] = ($index + 1);
            $list[$index]['title'] = $Directories[$i];

            $index++;
        } // for ($i=0; $i < $countDirectories; $i++) { 

        return $list;

    }

}
