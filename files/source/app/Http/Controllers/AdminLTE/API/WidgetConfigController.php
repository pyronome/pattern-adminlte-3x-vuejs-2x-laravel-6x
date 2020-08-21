<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\Http\Requests\AdminLTE\API\WidgetConfigPOSTRequest;

class WidgetConfigController extends Controller
{
    public function get(Request $request)
    {
        /* $pagename = isset($parameters['pagename'])
                ? htmlspecialchars($parameters['pagename'])
                : ''; */

        $pagename = 'color';

        $objectAdminLTE = new AdminLTE();

        $Widgets = $objectAdminLTE->getPageLayout($pagename);
        
        $widget_json = json_encode($Widgets,
                JSON_HEX_QUOT |
                JSON_HEX_TAG |
                JSON_HEX_AMP |
                JSON_HEX_APOS);

        return [
            'id' => 1,
            'widget_json' => $widget_json
        ];

    }

    public function post(WidgetConfigPOSTRequest $request)
    {

        $adminLTE = new AdminLTE();
        $adminLTE->updateDotEnv(
                'MAIL_FROM_NAME',
                $request->input('email_from_name'));

        $adminLTE->updateDotEnv(
                'MAIL_FROM_ADDRESS',
                $request->input('email_reply_to'));

        $adminLTE->updateDotEnv(
                'MAIL_HOST',
                $request->input('email_smtp_host'));

        $adminLTE->updateDotEnv(
                'MAIL_USERNAME',
                $request->input('email_smtp_user'));

        $adminLTE->updateDotEnv(
                'MAIL_PASSWORD',
                $request->input('email_smtp_password'));

        $adminLTE->updateDotEnv(
                'MAIL_ENCRYPTION',
                $request->input('email_smtp_encryption'));

        $adminLTE->updateDotEnv(
                'MAIL_PORT',
                $request->input('email_smtp_port'));

        return [
            'message' => __('Email server configuration saved.')
        ];

    }

}
