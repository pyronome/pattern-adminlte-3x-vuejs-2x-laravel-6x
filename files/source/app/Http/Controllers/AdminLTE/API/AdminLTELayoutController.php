<?php

namespace App\Http\Controllers\AdminLTE\HTMLDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE;
use App\AdminLTEUser;
use App\HTMLDB;

class AdminLTELayoutController extends Controller
{

    public $columns = [];
    public $protectedColumns = [];
    public $row = [];

    public function get_attributes(Request $request)
    {

        $this->columns = [
            'id',
            'model',
            'attribute'
        ];

        $list = array();

        $adminLTE = new AdminLTE();

        $Models = $adminLTE->getModelList();

        $countModels = count($Models);
        $index = 0;

        for ($i=0; $i < $countModels; $i++)
        {
            $model = ('\\App\\' . $Models[$i]);
            
            $object = new $model;
            $property_list = $adminLTE->getModelPropertyList($object);
            $countProperty = count($property_list);

            for ($j=0; $j < $countProperty; $j++) { 
                $property = $property_list[$j];

                $list[$index]['id'] = ($index + 1);
                $list[$index]['model'] = $model;
                $list[$index]['attribute'] = $property;

                $index++;

                $list[$index]['id'] = ($index + 1);
                $list[$index]['model'] = $model;
                $list[$index]['attribute'] = $property . '/display_text';

                $index++;

            } // for ($j=0; $j < $countProperty; $j++) { 
        } // for ($i=0; $i < $countModels; $i++) {

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;

    }

    public function get_widgetconfig(Request $request)
    {

        $this->columns = [
            'id',
            'widget_json'
        ];

        $list = array();

        $parameters = $request->route()->parameters();

        $pageName = isset($parameters['pageName'])
                ? htmlspecialchars($parameters['pageName'])
                : '';

        $adminLTE = new AdminLTE();

        $Widgets = $adminLTE->getPageLayout($pageName);

        $list[0]['id'] = 1;
        $list[0]['widget_json'] = json_encode($Widgets,
                JSON_HEX_QUOT |
                JSON_HEX_TAG |
                JSON_HEX_AMP |
                JSON_HEX_APOS);

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;

    }

    public function post(Request $request)
    {

        $objectHTMLDB = new HTMLDB();

        $this->row = $objectHTMLDB->requestPOSTRow(
                $request->all(),
                $this->columns,
                $this->protectedColumns,
                0,
                true);

        $result = $this->check();

        if (0 == $result['errorCount'])
        {
            $adminLTEUser = AdminLTEUser::where('email', $this->row['email'])
                    ->first();

            auth()->guard('adminlteuser')->login($adminLTEUser);

            $landingPage = config('adminlte.landing_page');

            if ($landingPage === false)
            {
                $landingPage = 'home';
            } // if ($landingPage === false)

            $result['messageCount'] = 1;
            $result['lastMessage'] = $landingPage;
        } // if (0 == $result['errorCount'])

        $objectHTMLDB->errorCount = $result['errorCount'];
        $objectHTMLDB->messageCount = $result['messageCount'];
        $objectHTMLDB->lastError = $result['lastError'];
        $objectHTMLDB->lastMessage = $result['lastMessage'];
        $objectHTMLDB->printResponseJSON();
        return;

    }

    public function check()
    {

        $result = [
            'lastError' => '',
            'errorCount' => 0,
            'lastMessage' => '',
            'messageCount' => 0
        ];

        /* {{snippet:begin_check_values}} */

        if ('' == $this->row['email'])
        {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('Please specify your email address.');
        } // if ('' == $this->row['email'])

        if ('' == $this->row['password'])
        {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('Please specify your password.');
        } // if ('' == $this->row['password'])

        if (0 == $result['errorCount']) {

            $adminLTEUser = AdminLTEUser::where('email', $this->row['email'])
                    ->first();
            
            $confirmed = false;

            if ($adminLTEUser != null)
            {
                if (password_verify($this->row['password'], $adminLTEUser->password))
                {
                    $confirmed = true;
                }
            } // if (null == $adminLTEUser)

            if (!$confirmed)
            {
                $result['errorCount']++;
                if ($result['lastError'] != '') {
                    $result['lastError'] .= '<br>';
                } // if ($result['lastError'] != '') {

                $result['lastError'] .= __('Your e-mail address or password is not correct. Please check and try again.');

                sleep(2);
            } // if (!$confirmed)

        }

        /* {{snippet:end_check_values}} */

        return $result;

    }

}
