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

    public function get_date_format_options(Request $request)
    {
        $list = [];
        $index = 0;

        $list[$index]['id'] = 'd/m/Y';
        $list[$index]['text'] = '15/06/1981';
        $index++;
        
        $list[$index]['id'] = 'j/n/Y';
        $list[$index]['text'] = '15/6/1981';
        $index++;
        
        $list[$index]['id'] = 'd/m/y';
        $list[$index]['text'] = '15/06/81';
        $index++;
        
        $list[$index]['id'] = 'j/n/y';
        $list[$index]['text'] = '15/6/81';
        $index++;
        
        $list[$index]['id'] = 'd-m-Y';
        $list[$index]['text'] = '15-06-1981';
        $index++;
        
        $list[$index]['id'] = 'j-n-Y';
        $list[$index]['text'] = '15-6-1981';
        $index++;
        
        $list[$index]['id'] = 'd-m-y';
        $list[$index]['text'] = '15-06-81';
        $index++;
        
        $list[$index]['id'] = 'j-n-y';
        $list[$index]['text'] = '15-6-81';
        $index++;
        
        $list[$index]['id'] = 'd.m.Y';
        $list[$index]['text'] = '15.06.1981';
        $index++;
        
        $list[$index]['id'] = 'j.n.Y';
        $list[$index]['text'] = '15.6.1981';
        $index++;
        
        $list[$index]['id'] = 'd.m.y';
        $list[$index]['text'] = '15.06.81';
        $index++;
        
        $list[$index]['id'] = 'j.n.y';
        $list[$index]['text'] = '15.6.81';
        $index++;
        
        $list[$index]['id'] = 'm/d/Y';
        $list[$index]['text'] = '06/15/1981';
        $index++;
        
        $list[$index]['id'] = 'n/j/Y';
        $list[$index]['text'] = '6/15/1981';
        $index++;
        
        $list[$index]['id'] = 'm/d/y';
        $list[$index]['text'] = '06/15/81';
        $index++;
        
        $list[$index]['id'] = 'n/j/y';
        $list[$index]['text'] = '6/15/81';
        $index++;
        
        $list[$index]['id'] = 'm-d-Y';
        $list[$index]['text'] = '06-15-1981';
        $index++;
        
        $list[$index]['id'] = 'n-j-Y';
        $list[$index]['text'] = '6-15-1981';
        $index++;
        
        $list[$index]['id'] = 'm-d-y';
        $list[$index]['text'] = '06-15-81';
        $index++;
        
        $list[$index]['id'] = 'n-j-y';
        $list[$index]['text'] = '6-15-81';
        $index++;
        
        $list[$index]['id'] = 'm.d.Y';
        $list[$index]['text'] = '06.15.1981';
        $index++;
        
        $list[$index]['id'] = 'n.j.Y';
        $list[$index]['text'] = '6.15.1981';
        $index++;
        
        $list[$index]['id'] = 'm.d.y';
        $list[$index]['text'] = '06.15.81';
        $index++;
        
        $list[$index]['id'] = 'n.j.y';
        $list[$index]['text'] = '6.15.81';
        $index++;
        
        $list[$index]['id'] = 'Y/m/d';
        $list[$index]['text'] = '1981/06/15';
        $index++;
        
        $list[$index]['id'] = 'Y/n/j';
        $list[$index]['text'] = '1981/6/15';
        $index++;
        
        $list[$index]['id'] = 'y/m/d';
        $list[$index]['text'] = '81/06/15';
        $index++;
        
        $list[$index]['id'] = 'y/n/j';
        $list[$index]['text'] = '81/6/15';
        $index++;
        
        $list[$index]['id'] = 'Y-m-d';
        $list[$index]['text'] = '1981-06-15';
        $index++;
        
        $list[$index]['id'] = 'Y-n-j';
        $list[$index]['text'] = '1981-6-15';
        $index++;
        
        $list[$index]['id'] = 'y-m-d';
        $list[$index]['text'] = '81-06-15';
        $index++;
        
        $list[$index]['id'] = 'y-n-j';
        $list[$index]['text'] = '81-6-15';
        $index++;
        
        $list[$index]['id'] = 'Y.m.d';
        $list[$index]['text'] = '1981.06.15';
        $index++;
        
        $list[$index]['id'] = 'Y.n.j';
        $list[$index]['text'] = '1981.6.15';
        $index++;
        
        $list[$index]['id'] = 'y.m.d';
        $list[$index]['text'] = '81.06.15';
        $index++;
        
        $list[$index]['id'] = 'y.n.j';
        $list[$index]['text'] = '81.6.15';
        $index++;
        
        $list[$index]['id'] = 'j F Y';
        $list[$index]['text'] = __('15 June 1981');
        $index++;
        
        $list[$index]['id'] = 'j F y';
        $list[$index]['text'] = __('15 June 81');
        $index++;
        
        $list[$index]['id'] = 'j M Y';
        $list[$index]['text'] = __('15 Jun 1981');
        $index++;
        
        $list[$index]['id'] = 'j M y';
        $list[$index]['text'] = __('15 Jun 81');
        $index++;
        
        $list[$index]['id'] = 'F j, Y';
        $list[$index]['text'] = __('June 15, 1981');
        $index++;
        
        $list[$index]['id'] = 'F j, y';
        $list[$index]['text'] = __('June 15, 81');
        $index++;
        
        $list[$index]['id'] = 'F j, Y';
        $list[$index]['text'] = __('Jun 15, 1981');
        $index++;
        
        $list[$index]['id'] = 'M j, y';
        $list[$index]['text'] = __('Jun 15, 81');
        $index++;
        
        return [
            'list' => $list
        ];
    }

    public function get_year_month_format_options(Request $request)
    {
        $list = [];
        $index = 0;

        $list[$index]['id'] = 'm/Y';
        $list[$index]['text'] = '06/1981';
        $index++;

        $list[$index]['id'] = 'n/Y';
        $list[$index]['text'] = '6/1981';
        $index++;

        $list[$index]['id'] = 'm/y';
        $list[$index]['text'] = '06/81';
        $index++;

        $list[$index]['id'] = 'n/y';
        $list[$index]['text'] = '6/81';
        $index++;

        $list[$index]['id'] = 'm-Y';
        $list[$index]['text'] = '06-1981';
        $index++;

        $list[$index]['id'] = 'n-Y';
        $list[$index]['text'] = '6-1981';
        $index++;

        $list[$index]['id'] = 'm-y';
        $list[$index]['text'] = '06-81';
        $index++;

        $list[$index]['id'] = 'n-y';
        $list[$index]['text'] = '6-81';
        $index++;

        $list[$index]['id'] = 'm.Y';
        $list[$index]['text'] = '06.1981';
        $index++;

        $list[$index]['id'] = 'n.Y';
        $list[$index]['text'] = '6.1981';
        $index++;

        $list[$index]['id'] = 'm.y';
        $list[$index]['text'] = '06.81';
        $index++;

        $list[$index]['id'] = 'n.y';
        $list[$index]['text'] = '6.81';
        $index++;

        $list[$index]['id'] = 'Y/m';
        $list[$index]['text'] = '1981/06';
        $index++;

        $list[$index]['id'] = 'Y/n';
        $list[$index]['text'] = '1981/6';
        $index++;

        $list[$index]['id'] = 'y/m';
        $list[$index]['text'] = '81/06';
        $index++;

        $list[$index]['id'] = 'y/n';
        $list[$index]['text'] = '81/6';
        $index++;

        $list[$index]['id'] = 'Y-m';
        $list[$index]['text'] = '1981-06';
        $index++;

        $list[$index]['id'] = 'Y-n';
        $list[$index]['text'] = '1981-6';
        $index++;

        $list[$index]['id'] = 'y-m';
        $list[$index]['text'] = '81-06';
        $index++;

        $list[$index]['id'] = 'y-n';
        $list[$index]['text'] = '81-6';
        $index++;

        $list[$index]['id'] = 'Y.m';
        $list[$index]['text'] = '1981.06';
        $index++;

        $list[$index]['id'] = 'Y.n';
        $list[$index]['text'] = '1981.6';
        $index++;

        $list[$index]['id'] = 'y.m';
        $list[$index]['text'] = '81.06';
        $index++;

        $list[$index]['id'] = 'y.n';
        $list[$index]['text'] = '81.6';
        $index++;

        $list[$index]['id'] = 'F Y';
        $list[$index]['text'] = __('June 1981');
        $index++;

        $list[$index]['id'] = 'F y';
        $list[$index]['text'] = __('June 81');
        $index++;

        $list[$index]['id'] = 'M Y';
        $list[$index]['text'] = __('Jun 1981');
        $index++;

        $list[$index]['id'] = 'M y';
        $list[$index]['text'] = __('Jun 81');
        $index++;

        return [
            'list' => $list
        ];
    }

    public function get_time_format_options(Request $request)
    {
        $list = [];
        $index = 0;

        
        $list[$index]['id'] = 'H:i';
        $list[$index]['text'] = '17:00';
        $index++;

        $list[$index]['id'] = 'h:i a';
        $list[$index]['text'] = '05:00 pm';
        $index++;

        $list[$index]['id'] = 'H:i:s';
        $list[$index]['text'] = '17:00:00';
        $index++;

        $list[$index]['id'] = 'h:i:s a';
        $list[$index]['text'] = '05:00:00 pm';
        $index++;

        return [
            'list' => $list
        ];
    }

    public function get_number_format_options(Request $request)
    {
        $list = [];
        $index = 0;

        $list[$index]['id'] = 'tr';
        $list[$index]['text'] = '1.000.000,00';
        $index++;

        $list[$index]['id'] = 'en';
        $list[$index]['text'] = '1,000,000.00';
        $index++;
        
        return [
            'list' => $list
        ];
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