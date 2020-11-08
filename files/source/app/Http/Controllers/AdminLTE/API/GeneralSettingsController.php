<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\Http\Requests\AdminLTE\API\GeneralSettingsPOSTRequest;

class GeneralSettingsController extends Controller
{

    public function get(Request $request)
    {

        return [
            'debug_mode' => (config('app.debug') ? 1 : 0),
            'project_title' => config('adminlte.project_title'),
            'main_folder' => config('adminlte.main_folder'),
            'landing_page' => config('adminlte.landing_page'),
            'default_language' => config('adminlte.default_language'),
            'timezone' => config('adminlte.timezone'),
            'date_format' => config('adminlte.date_format'),
            'time_format' => config('adminlte.time_format'),
            'year_month_format' => config('adminlte.year_month_format'),
            'number_format' => config('adminlte.number_format'),
            'google_maps_api_key' => config('adminlte.google_maps_api_key')
        ];

    }

    public function get_languages(Request $request)
    {

        return [
            ['text' => 'English', 'id' => 'en']
        ];

    }

    public function get_timezones(Request $request)
    {

        return \DateTimeZone::listIdentifiers();

    }

    public function post(GeneralSettingsPOSTRequest $request)
    {

        $variables = array();
        $variables['adminlte.project_title'] = $request->input('project_title');
        $variables['adminlte.main_folder'] = $request->input('main_folder');
        $variables['adminlte.landing_page'] = $request->input('landing_page');
        $variables['adminlte.default_language'] = $request->input('default_language');
        $variables['adminlte.timezone'] = $request->input('timezone');
        $variables['adminlte.date_format'] = $request->input('date_format');
        $variables['adminlte.time_format'] = $request->input('time_format');
        $variables['adminlte.year_month_format'] = $request->input('year_month_format');
        $variables['adminlte.number_format'] = $request->input('number_format');
        $variables['adminlte.google_maps_api_key'] = $request->input('google_maps_api_key');

        $root = dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))));
        $source_path = $root . '/config/adminlte.template.json';
        $destination_path = 'config/adminlte.json';
       
        $objectAdminLTE = new AdminLTE();
        $objectAdminLTE->writeTemplateFileToTarget($source_path, $destination_path, $variables);
  
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

}