<?php

namespace App\Http\Controllers\AdminLTE\HTMLDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE;
use App\AdminLTEUser;
use App\AdminLTEUserGroup;
use App\HTMLDB;

class AdminLTEUserController extends Controller
{

    public $columns = [];
    public $protectedColumns = [];
    public $row = [];

    public function get(Request $request)
    {
        
        $this->columns = [
            'id',
            'id/display_text',
            'deleted',
            'deleted/display_text',
            'created_at',
            'created_at/display_text',
            'updated_at',
            'updated_at/display_text',
            'enabled',
            'enabled/display_text',
            'adminlteusergroup_id',
            'adminlteusergroup_id/display_text',
            'fullname',
            'fullname/display_text',
            'username',
            'username/display_text',
            'email',
            'email/display_text',
            'password',
            'password/display_text',
            'menu_permission',
            'menu_permission/display_text',
            'service_permission',
            'service_permission/display_text',
            'profile_img',
            /*'profile_img/display_text',*/
            'group_menu_permission',
            'group_service_permission'
        ];

        $list = [];

        $parameters = $request->route()->parameters();
        
        $id = 0;
        if (isset($parameters['id'])) {
            $id = (isset($parameters['id']) ? intval($parameters['id']) : 0);
        } // if (isset($parameters['id'])) {
        
        $adminLTE = new AdminLTE();

        $objectAdminLTEUserGroup = null;
        $objectAdminLTEUsers = null;
        $objectAdminLTEUser = null;

        $objectAdminLTEUsers = \App\AdminLTEUser::where('deleted', false)
                ->where('id', $id)
                ->get();

        $index = 0;

        foreach ($objectAdminLTEUsers as $objectAdminLTEUser)
        {

            $list[$index]['id'] = $objectAdminLTEUser->id;
            $list[$index]['deleted'] = $objectAdminLTEUser->deleted;
            $list[$index]['created_at'] = $objectAdminLTEUser->created_at->timestamp;
            $list[$index]['updated_at'] = $objectAdminLTEUser->updated_at->timestamp;
            $list[$index]['enabled'] = $objectAdminLTEUser->enabled;
            $list[$index]['adminlteusergroup_id'] = $objectAdminLTEUser->adminlteusergroup_id;
            $list[$index]['fullname'] = $objectAdminLTEUser->fullname;
            $list[$index]['username'] = $objectAdminLTEUser->username;
            $list[$index]['email'] = $objectAdminLTEUser->email;
            $list[$index]['password'] = '';
            $list[$index]['menu_permission'] = $adminLTE->base64decode($objectAdminLTEUser->menu_permission);
            $list[$index]['service_permission'] = $adminLTE->base64decode($objectAdminLTEUser->service_permission);
            $list[$index]['profile_img'] = $objectAdminLTEUser->profile_img;

            // Display Texts
            $displayTexts = $adminLTE->getObjectDisplayTexts('AdminLTEUser', $objectAdminLTEUser);

            $list[$index]['id/display_text'] = $displayTexts['id'];
            $list[$index]['deleted/display_text'] = $displayTexts['deleted'];
            $list[$index]['created_at/display_text'] = $displayTexts['created_at'];
            $list[$index]['updated_at/display_text'] = $displayTexts['updated_at'];
            $list[$index]['enabled/display_text'] = $displayTexts['enabled'];
            $list[$index]['adminlteusergroup_id/display_text'] = $displayTexts['adminlteusergroup_id'];
            $list[$index]['fullname/display_text'] = $displayTexts['fullname'];
            $list[$index]['username/display_text'] = $displayTexts['username'];
            $list[$index]['email/display_text'] = $displayTexts['email'];
            $list[$index]['password/display_text'] = $displayTexts['password'];
            $list[$index]['menu_permission/display_text'] = $displayTexts['menu_permission'];
            $list[$index]['service_permission/display_text'] = $displayTexts['service_permission'];
            /*$list[$index]['profile_img/display_text'] = $displayTexts['profile_img'];*/
            
            // Other Configuration
            $list[$index]['group_menu_permission'] = '';
            $list[$index]['group_service_permission'] = '';
            
            if (0 != $objectAdminLTEUser->adminlteusergroup_id) {
                $objectAdminLTEUserGroup = AdminLTEUserGroup::find(
                        $objectAdminLTEUser->adminlteusergroup_id);

                if (null == $objectAdminLTEUserGroup)
                {
                    continue;
                } // if (null == $objectAdminLTEUserGroup)
                
                $list[$index]['group_menu_permission'] = $adminLTE->base64decode(
                        $objectAdminLTEUserGroup->menu_permission);
                $list[$index]['group_service_permission'] = $adminLTE->base64decode(
                        $objectAdminLTEUserGroup->service_permission);
            }

            $index++;

        } // foreach ($objectAdminLTEUsers as $objectAdminLTEUser)

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;

    }

    public function get_session(Request $request)
    {
        
        $this->columns = [
            'id',
            'searchText',
            'sortingColumn',
            'sortingASC',
            'page',
            'pageCount',
            'bufferSize'
        ];

        $adminLTE = new AdminLTE();
        $parameters = $request->route()->parameters();

        $list = [];

        $sessionParameters = $adminLTE->getModelSessionParameters(
                $request,
                'AdminLTEUser');
        
        if (!isset($sessionParameters['page'])) {
            $pageName = '';

            if (isset($parameters['pageName'])) {
                $pageName = htmlspecialchars($parameters['pageName']);
            } // if (isset($parameters['pageName'])) {

            $Widgets = $adminLTE->getPageLayout($pageName);
            $bufferSize = $adminLTE->getRecordListLimit(
                    $request,
                    $Widgets,
                    'AdminLTEUser');

            $pageCount = ceil(
                    AdminLTEUser::where('deleted', false)->count()
                    / $bufferSize);

            $adminLTE->setModelSessionParameters($request,
                    'AdminLTEUser',
                    [
                        'searchText' => '',
                        'sortingColumn' => 'id',
                        'sortingASC' => 2,
                        'page' => 0,
                        'pageCount' => $pageCount,
                        'bufferSize' => $bufferSize
                    ]
            );
        }

        $sessionParameters = $adminLTE->getModelSessionParameters(
                $request,
                'AdminLTEUser');

        $sessionParameters['id'] = 1;

        $columnCount = count($this->columns);

        for ($i = 0; $i < $columnCount; $i++) {
            $list[0][$this->columns[$i]]
                    = isset($sessionParameters[$this->columns[$i]])
                    ? $sessionParameters[$this->columns[$i]]
                    : '';
        } // for ($i = 0; $i < $columnCount; $i++) {

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;

    }

    public function get_recordlist(Request $request)
    {

        $dateFormat = config('adminlte.date_format');
        $timeFormat = config('adminlte.time_format');
        $parameters = $request->route()->parameters();
        
        $pageName = '';
        if (isset($parameters['pageName'])) {
            $pageName = htmlspecialchars($parameters['pageName']);
        } // if (isset($parameters['pageName'])) {

        $columns = [];
        $list = [];
        
        $adminLTE = new AdminLTE();

        $Widgets = $adminLTE->getPageLayout($pageName);
        $variables = $adminLTE->getRecordListValueVariables($Widgets, 'AdminLTEUser');

        if (0 == count($variables)) {
            $variables = array();
        } // if (0 == count($variables)) {

        $bufferSize = $adminLTE->getRecordListLimit(
                $request,
                $Widgets,
                'AdminLTEUser');
        $showLastRecord = $adminLTE->getRecordListType(
                $Widgets,
                'AdminLTEUser');

        if (0 == $bufferSize) {
            $bufferSize = 10;
        } // if (0 == $bufferSize) {
        
        if ($showLastRecord) {
            $sortingColumn = 'id';
            $sortingAscending = false;
            $searchText = '';
            $page = 0;
        } else {
            $sessionParameters = $adminLTE->getModelSessionParameters(
                    $request,
                    'AdminLTEUser');

            $sortingColumn = isset($sessionParameters['sortingColumn'])
                    ? htmlspecialchars($sessionParameters['sortingColumn'])
                    : 'id';

            if (false !== strpos($sortingColumn, 'DisplayText')) {
                $sortingColumn = $adminLTE->getModelForeignSortColumn(
                        'AdminLTEUser',
                        $sortingColumn);
            }

            $sortingAscending = isset($sessionParameters['sortingASC'])
                    ? (1 == intval($sessionParameters['sortingASC']))
                    : false;

            $searchText = isset($sessionParameters['searchText'])
                    ? $sessionParameters['searchText']
                    : '';
            
            /*$bufferSize = isset($sessionParameters['bufferSize'])
                    ? $sessionParameters['bufferSize']
                    : 10;*/

            $page = isset($sessionParameters['page'])
                    ? $sessionParameters['page']
                    : 0;
        }

        $defaultColumns = [
            'id/display_text',
            'deleted',
            'deleted/display_text',
            'created_at',
            'created_at/display_text',
            'updated_at',
            'updated_at/display_text',
            'enabled',
            'enabled/display_text',
            'adminlteusergroup_id',
            'adminlteusergroup_id/display_text',
            'fullname',
            'fullname/display_text',
            'username',
            'username/display_text',
            'email',
            'email/display_text',
            'password',
            'password/display_text',
            'menu_permission',
            'menu_permission/display_text',
            'service_permission',
            'service_permission/display_text',
            'profile_img',
            'profile_img/display_text'
        ];
        
        $countDefaultColumns = count($defaultColumns);
        $columns = array();
        $columns[] = 'id';

        for ($i=0; $i < $countDefaultColumns; $i++) {
            $defaultColumn = $defaultColumns[$i];

            if (in_array($defaultColumn, $variables)) {
                $columns[] = $defaultColumns[$i];
            } // if (in_array($defaultColumn, $variables)) {
        } // for ($i=0; $i < $countDefaultColumns; $i++) {
        
        $objectAdminLTEUsers = AdminLTEUser::where('deleted', false)
                ->orderBy($sortingColumn, (($sortingAscending) ? 'asc' : 'desc'))
                ->get();
        $objectAdminLTEUser = NULL;
        $index = 0;

        foreach ($objectAdminLTEUsers as $objectAdminLTEUser)
        {
            $list[$index]['id'] = $objectAdminLTEUser->id;

            if (in_array('deleted', $variables)) {
                $list[$index]['deleted'] = $objectAdminLTEUser->deleted;
            } // if (in_array('deleted', $variables)) {
            
            if (in_array('created_at', $variables)) {
                $list[$index]['created_at'] = $objectAdminLTEUser->created_at->timestamp;
            } // if (in_array('created_at', $variables)) {
            
            if (in_array('updated_at', $variables)) {
                $list[$index]['updated_at'] = $objectAdminLTEUser->updated_at;
            } // if (in_array('updated_at', $variables)) {

            if (in_array('enabled', $variables)) {
                $list[$index]['enabled'] = $objectAdminLTEUser->enabled;
            } // if (in_array('enabled', $variables)) {
            
            if (in_array('adminlteusergroup_id', $variables)) {
                $list[$index]['adminlteusergroup_id']
                        = $objectAdminLTEUser->adminlteusergroup_id;
            } // if (in_array('adminlteusergroup_id', $variables)) {

            if (in_array('fullname', $variables)) {
                $list[$index]['fullname'] = $objectAdminLTEUser->fullname;
            } // if (in_array('fullname', $variables)) {

            if (in_array('username', $variables)) {
                $list[$index]['username'] = $objectAdminLTEUser->username;
            } // if (in_array('username', $variables)) {

            if (in_array('email', $variables)) {
                $list[$index]['email'] = $objectAdminLTEUser->email;
            } // if (in_array('email', $variables)) {

            if (in_array('password', $variables)) {
                $list[$index]['password'] = $objectAdminLTEUser->password;
            } // if (in_array('password', $variables)) {

            if (in_array('menu_permission', $variables)) {
                $list[$index]['menu_permission'] = $adminLTE->base64decode(
                        $objectAdminLTEUser->menu_permission);
            } // if (in_array('menu_permission', $variables)) {
            
            if (in_array('service_permission', $variables)) {
                $list[$index]['service_permission'] = $adminLTE->base64decode(
                        $objectAdminLTEUser->service_permission);
            } // if (in_array('service_permission', $variables)) {
            
            if (in_array('profile_img', $variables)) {
                $list[$index]['profile_img'] = $objectAdminLTEUser->profile_img;
            } // if (in_array('profile_img', $variables)) {

            // Display Texts
            $displayTexts = $adminLTE->getObjectDisplayTexts(
                    'AdminLTEUser',
                    $objectAdminLTEUser);

            if (in_array('id/display_text', $variables)) {
                $list[$index]['id/display_text'] = $displayTexts['id'];
            } // if (in_array('id/display_text', $variables)) {
            
            if (in_array('created_at/display_text', $variables)) {
                $list[$index]['created_at/display_text'] = $displayTexts['created_at'];
            } // if (in_array('created_at/display_text', $variables)) {

            if (in_array('updated_at/display_text', $variables)) {
                $list[$index]['updated_at/display_text'] = $displayTexts['updated_at'];
            } // if (in_array('updated_at/display_text', $variables)) {

            if (in_array('deleted/display_text', $variables)) {
                $list[$index]['deleted/display_text'] = $displayTexts['deleted'];
            } // if (in_array('deleted/display_text', $variables)) {

            if (in_array('enabled/display_text', $variables)) {
                $list[$index]['enabled/display_text'] = $displayTexts['enabled'];
            } // if (in_array('enabled/display_text', $variables)) {

            if (in_array('adminlteusergroup_id/display_text', $variables)) {
                $list[$index]['adminlteusergroup_id/display_text']
                        = $displayTexts['adminlteusergroup_id'];
            } // if (in_array('adminlteusergroup_id/display_text', $variables)) {

            if (in_array('fullname/display_text', $variables)) {
                $list[$index]['fullname/display_text'] = $displayTexts['fullname'];
            } // if (in_array('fullname/display_text', $variables)) {

            if (in_array('username/display_text', $variables)) {
                $list[$index]['username/display_text'] = $displayTexts['username'];
            } // if (in_array('username/display_text', $variables)) {

            if (in_array('email/display_text', $variables)) {
                $list[$index]['email/display_text'] = $displayTexts['email'];
            } // if (in_array('email/display_text', $variables)) {

            if (in_array('password/display_text', $variables)) {
                $list[$index]['password/display_text'] = $displayTexts['password'];
            } // if (in_array('password/display_text', $variables)) {

            if (in_array('menu_permission/display_text', $variables)) {
                $list[$index]['menu_permission/display_text']
                        = $displayTexts['menu_permission'];
            } // if (in_array('menu_permission/display_text', $variables)) {

            if (in_array('service_permission/display_text', $variables)) {
                $list[$index]['service_permission/display_text']
                        = $displayTexts['service_permission'];
            } // if (in_array('service_permission/display_text', $variables)) {

            if (in_array('profile_img/display_text', $variables)) {
                $list[$index]['profile_img/display_text']
                        = $displayTexts['profile_img'];
            } // if (in_array('profile_img/display_text', $variables)) {

            $index++;
        } // foreach ($objectAdminLTEUsers as $objectAdminLTEUser)

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $columns;
        $objectHTMLDB->printHTMLDBList();
        return;

    }

    public function get_recordgraphdata(Request $request)
    {

        $columns = [
            'id',
            'data'
        ];

        $list = [];

        $dateFormat = config('adminlte.date_format');
        $yearMonthFormat = config('adminlte.year_month_format');
        $parameters = $request->route()->parameters();
        
        $adminLTE = new AdminLTE();

        $pageName = '';
        if (isset($parameters['pageName'])) {
            $pageName = htmlspecialchars($parameters['pageName']);
        } // if (isset($parameters['pageName'])) {

        $Widgets = $adminLTE->getPageLayout($pageName);
        $graphProperties = $adminLTE->getRecordGraphProperties(
                $Widgets,
                'AdminLTEUser');
        
        $graphType = $graphProperties['type'];

        $period = (0 - $graphProperties['period']);
        $fromDate = strtotime($period . ' month');

        $graphData = array();

        $objectAdminLTEUsers = \App\AdminLTEUser::where('deleted', false)
                ->where('created_at', '>=', $fromDate)
                ->orderBy('created_at', 'asc')
                ->get();

        $objectAdminLTEUser = NULL;
        $index = 0;
            
        if ('daily' == $graphType) {
            foreach ($objectAdminLTEUsers as $objectAdminLTEUser)
            {
                $created_at = $objectAdminLTEUser->created_at->format($dateFormat);

                if (!isset($graphData[$created_at])) {
                    $graphData[$created_at] = 0;
                }

                $graphData[$created_at]++;
            } // for ($i = 0; $i < $countAdminLTEUser; $i++) {
        } else if ('monthly' == $graphType) {
            foreach ($objectAdminLTEUsers as $objectAdminLTEUser)
            {
                $created_at = $objectAdminLTEUser->created_at->format($yearMonthFormat);

                if (!isset($graphData[$created_at])) {
                    $graphData[$created_at] = 0;
                }

                $graphData[$created_at]++;
            } // for ($i = 0; $i < $countAdminLTEUser; $i++) {
        } // if ('daily' == $graphType) {
        
        $keys = array_keys($graphData);
        $countKeys = count($keys);
        
        $graphJSON = '';
        for ($i=0; $i < $countKeys; $i++) {
            $created_at = $keys[$i];
            $countRecord = $graphData[$created_at];

            if ($graphJSON != '') {
                $graphJSON .= ',';
            } // if ($graphJSON != '') {

            $graphJSON .= '{';
            $graphJSON .= ('"date":"' . $created_at . '",');
            $graphJSON .= ('"record":' . $countRecord . '');
            $graphJSON .= '}';
        }
        $graphJSON = ('[' . $graphJSON . ']');


        $list[0]['id'] = 1;
        $list[0]['data'] = rawurlencode($graphJSON);

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }

    public function get_infoboxvalue(Request $request)
    {
        $columns = [
            'id',
            'model',
            'value'
        ];

        $list = array();
        $list[0]['id'] = 1;
        $list[0]['model'] = 'AdminLTEUser';
        
        $list[0]['value'] = \App\AdminLTEUser::where('deleted', false)->count();

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }

    public function get_form_delete(Request $request)
    {
        $columns = [
            'id',
            'idcsv'
        ];

        $list = array();

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }

    public function post_session(Request $request)
    {

        $adminLTE = new AdminLTE();
        $objectHTMLDB = new HTMLDB();

        $sessionParameters = $adminLTE->getModelSessionParameters(
                $request,
                'AdminLTEUser');

        $this->columns = [
            'searchText',
            'sortingColumn',
            'sortingASC',
            'page',
            'bufferSize',
            'pageCount'
        ];

        $this->row = $objectHTMLDB->requestPOSTRow(
                $request->all(),
                $this->columns,
                $this->protectedColumns,
                0,
                true);

        $sessionParameters['searchText']
                = isset($this->row['searchText'])
                ? htmlspecialchars($this->row['searchText'])
                : $sessionParameters['searchText'];

        $sessionParameters['sortingColumn']
                = isset($this->row['sortingColumn'])
                ? htmlspecialchars($this->row['sortingColumn'])
                : $sessionParameters['sortingColumn'];

        $sessionParameters['sortingASC']
                = isset($this->row['sortingASC'])
                ? intval($this->row['sortingASC'])
                : $sessionParameters['sortingASC'];

        $sessionParameters['page']
                = isset($this->row['page'])
                ? intval($this->row['page'])
                : $sessionParameters['page'];

        $sessionParameters['bufferSize']
                = isset($this->row['bufferSize'])
                ? intval($this->row['bufferSize'])
                : $sessionParameters['bufferSize'];
        
        if (0 == $sessionParameters['bufferSize'])
        {
            $sessionParameters['pageCount'] = 0;
        }
        else
        {
            $sessionParameters['pageCount'] = ceil(
                    \App\AdminLTEUser::where('deleted', false)->count()
                    / $sessionParameters['bufferSize']);
        } // if (0 == $sessionParameters['bufferSize'])

        $adminLTE->setModelSessionParameters($request,
                'AdminLTEUser',
                $sessionParameters);

        $objectHTMLDB->printResponseJSON();
        return;

    }

    public function post(Request $request)
    {
        loadLanguageFile('adminlteuser', 'adminlte');
        
        $controller->errorCount = 0;
        $controller->messageCount = 0;
        $controller->lastError = '';
        $controller->lastMessage = '';

        $currentAdminLTEUser = null;

        includeModel('AdminLTEUser');
        
        $id = isset($_REQUEST['htmldb_row0_id'])
            ? intval($_REQUEST['htmldb_row0_id'])
            : 0;

        if (0 != $id) {
            $currentAdminLTEUser = new AdminLTEUser($id);
        }

        $fullname = isset($_REQUEST['htmldb_row0_fullname'])
            ? htmlspecialchars($_REQUEST['htmldb_row0_fullname'])
            : '';
        
        $username = isset($_REQUEST['htmldb_row0_username'])
            ? htmlspecialchars($_REQUEST['htmldb_row0_username'])
            : '';

        includeLibrary('convertNameToFileName');
        $username = convertNameToFileName($username);

        $email = isset($_REQUEST['htmldb_row0_email'])
            ? htmlspecialchars($_REQUEST['htmldb_row0_email'])
            : '';

        $password = isset($_REQUEST['htmldb_row0_password'])
            ? htmlspecialchars($_REQUEST['htmldb_row0_password'])
            : '';

        if ('' == $fullname) {
            $controller->errorCount++;

            if ($controller->lastError != '') {
                $controller->lastError .= '<br>';
            } // if ($controller->lastError != '') {

            $controller->lastError .= __('Please specify fullname.');
        } // if ('' == $fullname) {

        if ('' == $username) {

            $controller->errorCount++;
            if ($controller->lastError != '') {
                $controller->lastError .= '<br>';
            } // if ($controller->lastError != '') {

            $controller->lastError .= __('Please specify username.');
        } else {
            $listAdminLTEUser = new AdminLTEUser();
            $listAdminLTEUser->addFilter('deleted', '==', false);
            $listAdminLTEUser->addFilter('username', '==', $username);
            if (null !== $currentAdminLTEUser) {
                $listAdminLTEUser->addFilter('id', '!=', $currentAdminLTEUser->id);
            }
            $listAdminLTEUser->bufferSize = 1;
            $listAdminLTEUser->page = 0;
            $listAdminLTEUser->find();

            if ($listAdminLTEUser->listCount > 0) {

                $controller->errorCount++;
                if ($controller->lastError != '') {
                    $controller->lastError .= '<br>';
                } // if ($controller->lastError != '') {

                $controller->lastError .= __('Username specified belongs to another user. Please specify another username.');

            } // if ($listAdminLTEUser->listCount > 0) {
        } // if ('' == $username) {  
        
        if ('' == $email) {

            $controller->errorCount++;
            if ($controller->lastError != '') {
                $controller->lastError .= '<br>';
            } // if ($controller->lastError != '') {

            $controller->lastError .= __('Please specify email address.');
        } else {
            includeLibrary('validateEmailAddress');
            if (!validateEmailAddress($email)) {
                $controller->errorCount++;
                if ($controller->lastError != '') {
                    $controller->lastError .= '<br>';
                } // if ($controller->lastError != '') {

                $controller->lastError .= __('Please specify a valid email address.');
            } else {
                $listAdminLTEUser = new AdminLTEUser();
                $listAdminLTEUser->addFilter('deleted', '==', false);
                $listAdminLTEUser->addFilter('email', '==', $email);
                if (null !== $currentAdminLTEUser) {
                    $listAdminLTEUser->addFilter('id', '!=', $currentAdminLTEUser->id);
                }
                $listAdminLTEUser->bufferSize = 1;
                $listAdminLTEUser->page = 0;
                $listAdminLTEUser->find();

                if ($listAdminLTEUser->listCount > 0) {

                    $controller->errorCount++;
                    if ($controller->lastError != '') {
                        $controller->lastError .= '<br>';
                    } // if ($controller->lastError != '') {

                    $controller->lastError .= __('E-mail address specified belongs to another user. Please specify another e-mail address.');

                } // if ($listAdminLTEUser->listCount > 0) {
            }
        }

        if ((0 == $id) && ('' == $password)) {
            $controller->errorCount++;
            if ($controller->lastError != '') {
                $controller->lastError .= '<br>';
            } // if ($controller->lastError != '') {

            $controller->lastError .= __('Please specify password.');
        }

        includeLibrary('convertNameToFileName');
        includeLibrary('adminlte/base64encode');

        includeModel('AdminLTEUser');
        $objectAdminLTEUser = new AdminLTEUser();
        $objectAdminLTEUser->request($_REQUEST, 'htmldb_row0_');

        $objectAdminLTEUser->username = convertNameToFileName($objectAdminLTEUser->username);
        
        $objectAdminLTEUser->menu_permission = base64encode($objectAdminLTEUser->menu_permission);
        $objectAdminLTEUser->service_permission = base64encode($objectAdminLTEUser->service_permission);

        $password = isset($_REQUEST['htmldb_row0_password'])
            ? htmlspecialchars($_REQUEST['htmldb_row0_password'])
            : '';

        if ('' != $password) {
            $objectAdminLTEUser->password = $password;
        }

        $objectAdminLTEUser->profile_img = 'media/user_images/default.jpg';

        $objectAdminLTEUser->update();

        $_SESSION[sha1('adminlteuser_lastid')] = $objectAdminLTEUser->id;

        return;

    }

    public function delete(Request $request)
    {
        $idcsv = isset($_REQUEST['htmldb_row0_idcsv'])
            ? htmlspecialchars($_REQUEST['htmldb_row0_idcsv'])
            : '';

        if ('' == $idcsv) {

            $controller->errorCount++;
            if ($controller->lastError != '') {
                $controller->lastError .= '<br>';
            } // if ($controller->lastError != '') {

            $controller->lastError .= __('Please select records.');
        } // if ('' == $idcsv) {

        $ids = explode(',', $idcsv);
        $idCount = count($ids);
        
        includeModel('AdminLTEUser');
        $objectAdminLTEUser = new AdminLTEUser();

        for ($i=0; $i < $idCount; $i++) { 
            $objectAdminLTEUser->id = $ids[$i];
            $objectAdminLTEUser->revert();
            $objectAdminLTEUser->deleted = 1;
            $objectAdminLTEUser->update();
        }

        if ($idCount > 0) {
            includeLibrary('getModelSessionParameters');
            $sessionParameters = getModelSessionParameters('AdminLTEUser');

            $listAdminLTEUser = new AdminLTEUser();
            $listAdminLTEUser->bufferSize = 1;
            $listAdminLTEUser->page = 0;
            $listAdminLTEUser->addFilter('deleted','==', false);
            $listAdminLTEUser->addSearchText($sessionParameters['searchText']);
            $listAdminLTEUser->find();

            $sessionParameters['pageCount'] = ceil($listAdminLTEUser->getPageCount() / $sessionParameters['bufferSize']);
            
            if ($sessionParameters['page'] == $sessionParameters['pageCount']) {
                if ($sessionParameters['page'] > 0) {
                    $sessionParameters['page']--;
                }
            }

            includeLibrary('setModelSessionParameters');
            setModelSessionParameters('AdminLTEUser', $sessionParameters);
        }

        $controller->messageCount = 1;
        $controller->lastMessage = 'UPDATED';
    }

}
