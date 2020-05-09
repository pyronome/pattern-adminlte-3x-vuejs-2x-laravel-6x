<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE;
use App\AdminLTEUser;

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
            ['name' => 'English', 'iso' => 'en']
        ];

    }

    public function get_timezones(Request $request)
    {

        return \DateTimeZone::listIdentifiers();

    }

    public function post(GeneralSettingsPOSTRequest $request)
    {

        $adminLTE = new AdminLTE();
        $adminLTE->updateDotEnv(
                'ADMINLTE_PROJECT_TITLE',
                $request->input('project_title'));

        $adminLTE->updateDotEnv(
                'ADMINLTE_MAIN_FOLDER',
                $request->input('main_folder'));

        $adminLTE->updateDotEnv(
                'ADMINLTE_LANDING_PAGE',
                $request->input('landing_page'));

        $adminLTE->updateDotEnv(
                'ADMINLTE_DEFAULT_LANGUAGE',
                $request->input('default_language'));

        $adminLTE->updateDotEnv(
                'ADMINLTE_TIMEZONE',
                $request->input('timezone'));

        $adminLTE->updateDotEnv(
                'ADMINLTE_DATE_FORMAT',
                $request->input('date_format'));

        $adminLTE->updateDotEnv(
                'ADMINLTE_TIME_FORMAT',
                $request->input('time_format'));

        $adminLTE->updateDotEnv(
                'ADMINLTE_YEAR_MONTH_FORMAT',
                $request->input('year_month_format'));

        $adminLTE->updateDotEnv(
                'ADMINLTE_NUMBER_FORMAT',
                $request->input('number_format'));

        $adminLTE->updateDotEnv(
                'ADMINLTE_GOOGLE_MAPS_API_KEY',
                $request->input('google_maps_api_key'));

    }

}
