<?php

namespace App\Http\Controllers\AdminLTE\HTMLDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\HTMLDB\HTMLDB;

class AdminLTEUserController extends Controller
{
    public $columns = [];
    public $protectedColumns = [];
    public $row = [];
    public $form_columns = [
        'id',
        'enabled',
        'adminlteusergroup_id',
        'fullname',
        'username',
        'email',
        'password'
        ];
    public $form_delete_columns = [
        'id',
        'idcsv'
    ];

    public function check()
    {
        $result = [
            'lastError' => '',
            'errorCount' => 0,
            'lastMessage' => '',
            'messageCount' => 0
        ];

        /* {{@snippet:begin_check_values}} */
        
        if ((0 == $this->row['id']) && ('' == $this->row['password'])) {
            $result['errorCount']++;

            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= 'Please specify password.';
        }

        /* {{@snippet:end_check_values}} */

        return $result;
    }

    public function check_delete()
    {
        $result = [
            'lastError' => '',
            'errorCount' => 0,
            'lastMessage' => '',
            'messageCount' => 0
        ];

        /* {{@snippet:begin_check_values}} */

        if ('' == $this->row['idcsv'])
        {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('Please select records.');
        } // if ('' == $this->row['idcsv'])

        /* {{@snippet:end_check_values}} */

        return $result;
    }

    public function delete(Request $request)
    {
        $objectHTMLDB = new HTMLDB();

        $this->row = $objectHTMLDB->requestPOSTRow(
                $request->all(),
                $this->form_delete_columns,
                $this->protectedColumns,
                0,
                false);

        $result = $this->check_delete();

        if (0 == $result['errorCount'])
        {
            $idcsv = $this->row['idcsv'];
            $ids = explode(',', $idcsv);
            $idCount = count($ids);

            if ($idCount > 0)
            {
                $objects = null;
                $object = null;

                $objects = AdminLTEUser::where('deleted', false)
                        ->whereIn('id', $ids)
                        ->get();

                foreach ($objects as $object)
                {
                    $object->deleted = 1;
                    $object->save();                
                } // foreach ($objects as $object)

                /*
                ::must_update::
                kullanılabilecek kodlar
                AdminLTEUser::where('deleted', false)->count();


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
                setModelSessionParameters('AdminLTEUser', $sessionParameters); */
            }
 
        } // if (0 == $result['errorCount'])

        if (0 == $result['errorCount']) {
            $result['messageCount'] = 1;
            $result['lastMessage'] = 'UPDATED';
        } // if (0 == $result['errorCount']) {

        $objectHTMLDB->errorCount = $result['errorCount'];
        $objectHTMLDB->messageCount = $result['messageCount'];
        $objectHTMLDB->lastError = $result['lastError'];
        $objectHTMLDB->lastMessage = $result['lastMessage'];
        $objectHTMLDB->printResponseJSON();
        return;
    }
    
    public function get(Request $request)
    {
        // start: check user get permission
        /*
        ::must_update:: servis izinleri nasıl kontrol ediliyor ?

        $directoryName = basename(dirname(__FILE__));
        $fileName = basename(__FILE__);
    
        includeLibrary('adminlte/checkUserGetPermission');
        $permissionResult = checkUserGetPermission($directoryName, $fileName);
    
        if ($permissionResult['error']) {
            $controller->errorCount = 1;
            $controller->lastError = $permissionResult['error_msg'];
            return false;
        }*/
        // end: check user get permission
        
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
            'password'
        ];
    
        $list = [];
        
        $parameters = $request->route()->parameters();
        
        if (!isset($parameters['id'])) {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->list = $list;
            $objectHTMLDB->columns = $this->columns;
            $objectHTMLDB->printHTMLDBList();
            return;
        } // if (!isset($parameters['id'])) {
        
        // is new ?
        if ('new' == htmlspecialchars($parameters['id'])) {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->list = $list;
            $objectHTMLDB->columns = $this->columns;
            $objectHTMLDB->printHTMLDBList();
            return;
        } // if (isset($parameters['id']) && ('new' == htmlspecialchars($parameters['id']))) {

        // is list ?
        if ('list' == htmlspecialchars($parameters['id'])) {
            $dateFormat = config('adminlte.date_format');
            $timeFormat = config('adminlte.time_format');
            
            $objectAdminLTE = new AdminLTE();
            
            $objectAdminLTEUserList = AdminLTEUser::where('deleted', false)
                    ->orderBy('created_at', 'asc')
                    ->get();
        } else if (intval($parameters['id']) > 0) {
            $objectAdminLTEUserList[] = AdminLTEUser::where('id', intval($parameters['id']))->first();
        } else {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->list = $list;
            $objectHTMLDB->columns = $this->columns;
            $objectHTMLDB->printHTMLDBList();
            return;
        }

        $objectAdminLTE = new AdminLTE();
        $objectAdminLTEUser = NULL;
        $index = 0;

        foreach ($objectAdminLTEUserList as $objectAdminLTEUser)
        {
            $displayTexts = $objectAdminLTE->getObjectDisplayTexts('AdminLTEUser', $objectAdminLTEUser);

            $list[$index]['id'] = $objectAdminLTEUser->id;
            $list[$index]['id/display_text'] = $displayTexts['id'];
            $list[$index]['deleted'] = $objectAdminLTEUser->deleted;
            $list[$index]['deleted/display_text'] = $displayTexts['deleted'];
            $list[$index]['created_at'] = $objectAdminLTEUser->created_at;
            $list[$index]['created_at/display_text'] = $displayTexts['created_at'];
            $list[$index]['updated_at'] = $objectAdminLTEUser->updated_at;
            $list[$index]['updated_at/display_text'] = $displayTexts['updated_at'];
            $list[$index]['enabled'] = $objectAdminLTEUser->enabled;
            $list[$index]['enabled/display_text'] = $displayTexts['enabled'];
            $list[$index]['adminlteusergroup_id'] = $objectAdminLTEUser->adminlteusergroup_id;
            $list[$index]['adminlteusergroup_id/display_text'] = $displayTexts['adminlteusergroup_id'];
            $list[$index]['fullname'] = $objectAdminLTEUser->fullname;
            $list[$index]['fullname/display_text'] = $displayTexts['fullname'];
            $list[$index]['username'] = $objectAdminLTEUser->username;
            $list[$index]['username/display_text'] = $displayTexts['username'];
            $list[$index]['email'] = $objectAdminLTEUser->email;
            $list[$index]['email/display_text'] = $displayTexts['email'];
            $list[$index]['password'] = '';

            $index++;
        } // foreach ($objectAdminLTEUserList as $objectAdminLTEUser)

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }
    
    public function get_files(Request $request) {
        // start: check user get permission
        /*
        ::must_update:: servis izinleri nasıl kontrol ediliyor ?

        $directoryName = basename(dirname(__FILE__));
        $fileName = basename(__FILE__);
    
        includeLibrary('adminlte/checkUserGetPermission');
        $permissionResult = checkUserGetPermission($directoryName, $fileName);
    
        if ($permissionResult['error']) {
            $controller->errorCount = 1;
            $controller->lastError = $permissionResult['error_msg'];
            return false;
        }*/
        // end: check user get permission
        
        $columns = [
            'id',
            'object_property',
            'file_name',
            'path',
            'media_type',
            'extension'
        ];
    
        $list = [];
        
        $parameters = $request->route()->parameters();
        
        if (!isset($parameters['id'])) {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->list = $list;
            $objectHTMLDB->columns = $columns;
            $objectHTMLDB->printHTMLDBList();
            return;
        } // if (!isset($parameters['id'])) {

        $object_id = intval($parameters['id']);

        if (0 == $object_id) {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->list = $list;
            $objectHTMLDB->columns = $columns;
            $objectHTMLDB->printHTMLDBList();
            return;
        } // if (!isset($parameters['id'])) {
        
        $objectAdminLTE = new AdminLTE();
        $files = $objectAdminLTE->getModelFiles('AdminLTEUser', $object_id);
        $index = 0;

        foreach ($files as $fileData) {
            $list[$index]["id"] = $fileData["id"];
            $list[$index]["object_property"] = $fileData["object_property"];
            $list[$index]["file_name"] = $fileData["file_name"];
            $list[$index]["path"] = $fileData["path"];
            $list[$index]["media_type"] = $fileData["media_type"];

            $fileNameTokens = explode('.', $fileData["file_name"]);
            $list[$index]["extension"] = strtolower(end($fileNameTokens));

            $index++;
        }

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }

    public function get_form_delete(Request $request)
    {
        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = array();
        $objectHTMLDB->columns = $this->form_delete_columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }

    public function get_infoboxvalue(Request $request)
    {
        $this->columns = [
            'id',
            'model',
            'value'
        ];

        $list = array();
        $list[0]['id'] = 1;
        $list[0]['model'] = 'AdminLTEUser';
        
        $list[0]['value'] = AdminLTEUser::where('deleted', false)->count();

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }
    
    public function get_option_list(Request $request)
    {
        // start: check user get permission
        /*
        ::must_update:: servis izinleri nasıl kontrol ediliyor ?

        $directoryName = basename(dirname(__FILE__));
        $fileName = basename(__FILE__);
    
        includeLibrary('adminlte/checkUserGetPermission');
        $permissionResult = checkUserGetPermission($directoryName, $fileName);
    
        if ($permissionResult['error']) {
            $controller->errorCount = 1;
            $controller->lastError = $permissionResult['error_msg'];
            return false;
        }*/
        // end: check user get permission
        
        $this->columns = [
            'id',
            'title',
            'value'
        ];
    
        $list = [];
        
        $parameters = $request->route()->parameters();

        $propertyName = '';
        
        if (isset($parameters['propertyName'])) {
            $propertyName = htmlspecialchars($parameters['propertyName']);
        } // if (isset($parameters['propertyName'])) {
        
        if ('' == $propertyName) {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->list = $list;
            $objectHTMLDB->columns = $this->columns;
            $objectHTMLDB->printHTMLDBList();
            return;
        } // if ('' == $propertyName) {
        
        $methodName = 'get_' . $propertyName . '_list';

        $defaultlObject = new AdminLTEUser();
        
        $options = $defaultlObject->$methodName();
        $index = 0;

        foreach ($options as $key => $value)
        {
            $list[$index]['id'] = $index;
            $list[$index]['value'] = $key;
            $list[$index]['title'] = $value;

            $index++;
        } // foreach ($options as $key => $value)

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }

    public function get_recordgraphdata(Request $request)
    {

        $this->columns = [
            'id',
            'data'
        ];

        $list = [];

        $dateFormat = config('adminlte.date_format');
        $yearMonthFormat = config('adminlte.year_month_format');
        $parameters = $request->route()->parameters();
        
        $objectAdminLTE = new AdminLTE();

        $pageName = '';
        if (isset($parameters['pageName'])) {
            $pageName = htmlspecialchars($parameters['pageName']);
        } // if (isset($parameters['pageName'])) {

        $Widgets = $objectAdminLTE->getPageLayout($pageName);
        $graphProperties = $objectAdminLTE->getRecordGraphProperties(
                $Widgets,
                'AdminLTEUser');
        
        $graphType = $graphProperties['type'];

        $period = (0 - $graphProperties['period']);
        $fromDate = strtotime($period . ' month');

        $graphData = array();

        $objectAdminLTEUsers = AdminLTEUser::where('deleted', false)
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
        
        $objectAdminLTE = new AdminLTE();

        $Widgets = $objectAdminLTE->getPageLayout($pageName);
        $variables = $objectAdminLTE->getRecordListValueVariables($Widgets, 'AdminLTEUser');

        if (0 == count($variables)) {
            $variables = array();
        } // if (0 == count($variables)) {

        $bufferSize = $objectAdminLTE->getRecordListLimit(
                $request,
                $Widgets,
                'AdminLTEUser');
        $showLastRecord = $objectAdminLTE->getRecordListType(
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
            $sessionParameters = $objectAdminLTE->getModelSessionParameters(
                    $request,
                    'AdminLTEUser');

            $sortingColumn = isset($sessionParameters['sortingColumn'])
                    ? htmlspecialchars($sessionParameters['sortingColumn'])
                    : 'id';

            if (false !== strpos($sortingColumn, 'DisplayText')) {
                $sortingColumn = $objectAdminLTE->getModelForeignSortColumn(
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
            'email/display_text'
        ];
        
        $countDefaultColumns = count($defaultColumns);
        $this->columns = array();
        $this->columns[] = 'id';

        for ($i=0; $i < $countDefaultColumns; $i++) {
            $defaultColumn = $defaultColumns[$i];

            if (in_array($defaultColumn, $variables)) {
                $this->columns[] = $defaultColumns[$i];
            } // if (in_array($defaultColumn, $variables)) {
        } // for ($i=0; $i < $countDefaultColumns; $i++) {
        
        $objectAdminLTEUsers = AdminLTEUser::where('deleted', false)
                ->orderBy($sortingColumn, (($sortingAscending) ? 'asc' : 'desc'))
                ->get();
        $objectAdminLTEUser = NULL;
        $index = 0;

        foreach ($objectAdminLTEUsers as $objectAdminLTEUser)
        {
            $displayTexts = $objectAdminLTE->getObjectDisplayTexts('AdminLTEUser', $objectAdminLTEUser);

            $list[$index]['id'] = $objectAdminLTEUser->id;
        
            if (in_array('id/display_text', $variables)) {
                $list[$index]['id/display_text'] = $displayTexts['id'];
            } // if (in_array('id/display_text', $variables)) {

            if (in_array('deleted', $variables)) {
                $list[$index]['deleted'] = $objectAdminLTEUser->deleted;
            } // if (in_array('deleted', $variables)) {

            if (in_array('deleted/display_text', $variables)) {
                $list[$index]['deleted/display_text'] = $displayTexts['deleted'];
            } // if (in_array('deleted/display_text', $variables)) {

            if (in_array('created_at', $variables)) {
                $list[$index]['created_at'] = $objectAdminLTEUser->created_at;
            } // if (in_array('created_at', $variables)) {

            if (in_array('created_at/display_text', $variables)) {
                $list[$index]['created_at/display_text'] = $displayTexts['created_at'];
            } // if (in_array('created_at/display_text', $variables)) {
            
            if (in_array('updated_at', $variables)) {
                $list[$index]['updated_at'] = $objectAdminLTEUser->updated_at;
            } // if (in_array('updated_at', $variables)) {

            if (in_array('updated_at/display_text', $variables)) {
                $list[$index]['updated_at/display_text'] = $displayTexts['updated_at'];
            } // if (in_array('updated_at/display_text', $variables)) {
    
            if (in_array('enabled', $variables)) {
                $list[$index]['enabled'] = $objectAdminLTEUser->enabled;
            } // if (in_array('enabled', $variables)) {

            if (in_array('enabled/display_text', $variables)) {
                $list[$index]['enabled/display_text'] = $displayTexts['enabled'];
            } // if (in_array('enabled/display_text', $variables)) {  
            
            if (in_array('adminlteusergroup_id', $variables)) {
                $list[$index]['adminlteusergroup_id'] = $objectAdminLTEUser->adminlteusergroup_id;
            } // if (in_array('adminlteusergroup_id', $variables)) {

            if (in_array('adminlteusergroup_id/display_text', $variables)) {
                $list[$index]['adminlteusergroup_id/display_text'] = $displayTexts['adminlteusergroup_id'];
            } // if (in_array('adminlteusergroup_id/display_text', $variables)) {
    
            if (in_array('fullname', $variables)) {
                $list[$index]['fullname'] = $objectAdminLTEUser->fullname;
            } // if (in_array('fullname', $variables)) {

            if (in_array('fullname/display_text', $variables)) {
                $list[$index]['fullname/display_text'] = $displayTexts['fullname'];
            } // if (in_array('fullname/display_text', $variables)) {  
            if (in_array('username', $variables)) {
                $list[$index]['username'] = $objectAdminLTEUser->username;
            } // if (in_array('username', $variables)) {

            if (in_array('username/display_text', $variables)) {
                $list[$index]['username/display_text'] = $displayTexts['username'];
            } // if (in_array('username/display_text', $variables)) {  
            if (in_array('email', $variables)) {
                $list[$index]['email'] = $objectAdminLTEUser->email;
            } // if (in_array('email', $variables)) {

            if (in_array('email/display_text', $variables)) {
                $list[$index]['email/display_text'] = $displayTexts['email'];
            } // if (in_array('email/display_text', $variables)) {

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

        $objectAdminLTE = new AdminLTE();
        $parameters = $request->route()->parameters();

        $list = [];

        $sessionParameters = $objectAdminLTE->getModelSessionParameters(
                $request,
                'AdminLTEUser');
        
        if (!isset($sessionParameters['page'])) {
            $pageName = '';

            if (isset($parameters['pageName'])) {
                $pageName = htmlspecialchars($parameters['pageName']);
            } // if (isset($parameters['pageName'])) {

            $Widgets = $objectAdminLTE->getPageLayout($pageName);
            $bufferSize = $objectAdminLTE->getRecordListLimit(
                    $request,
                    $Widgets,
                    'AdminLTEUser');

            $pageCount = ceil(
                    AdminLTEUser::where('deleted', false)->count()
                    / $bufferSize);

            $objectAdminLTE->setModelSessionParameters($request,
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

        $sessionParameters = $objectAdminLTE->getModelSessionParameters(
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
    
    public function post(Request $request)
    {
        //loadLanguageFile('adminlteuser', 'adminlte');
        
        $objectHTMLDB = new HTMLDB();

        $this->row = $objectHTMLDB->requestPOSTRow(
                $request->all(),
                $this->form_columns,
                $this->protectedColumns,
                0,
                false);
        
        $result = $this->check();

        if (0 == $result['errorCount'])
        {
            $id = intval($this->row['id']);

            if ($id > 0) {
                $objectAdminLTEUser = AdminLTEUser::find($id);
            } else {
                $objectAdminLTEUser = new AdminLTEUser();
            } // if ($id > 0) {

            $objectAdminLTEUser->deleted = 0;

            $objectAdminLTEUser->enabled = ('' != $this->row['enabled'])
                    ? intval($this->row['enabled'])
                    : 0;
            $objectAdminLTEUser->adminlteusergroup_id = ('' != $this->row['adminlteusergroup_id'])
                    ? intval($this->row['adminlteusergroup_id'])
                    : 0;

            $objectAdminLTEUser->fullname = $this->row['fullname'];
            $objectAdminLTEUser->username = $this->row['username'];
            $objectAdminLTEUser->email = $this->row['email'];

            if ('' != $this->row['password']) {
                $objectAdminLTEUser->password = bcrypt($this->row['password']);
            }

            $objectAdminLTEUser->save();

            $request->session()->put(sha1('adminlteuser_lastid'), $objectAdminLTEUser->id);
        } // if (0 == $result['errorCount']) {
        
        $objectHTMLDB->lastError = $result['lastError'];
        $objectHTMLDB->errorCount = $result['errorCount'];
        $objectHTMLDB->lastMessage = $result['lastMessage'];
        $objectHTMLDB->messageCount = $result['messageCount'];

        $objectHTMLDB->printResponseJSON();
        return;
    }

    public function post_session(Request $request)
    {

        $objectAdminLTE = new AdminLTE();
        $objectHTMLDB = new HTMLDB();

        $sessionParameters = $objectAdminLTE->getModelSessionParameters(
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
                    AdminLTEUser::where('deleted', false)->count()
                    / $sessionParameters['bufferSize']);
        } // if (0 == $sessionParameters['bufferSize'])

        $objectAdminLTE->setModelSessionParameters($request,
                'AdminLTEUser',
                $sessionParameters);

        $objectHTMLDB->printResponseJSON();
        return;
    }
}
