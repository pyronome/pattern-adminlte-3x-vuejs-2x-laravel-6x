<?php

namespace App\Http\Controllers\AdminLTE\HTMLDB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE;
use App\AdminLTEUser;
use App\HTMLDB;

class MediaController extends Controller
{
    public $columns = [
        'id',
        'object_property',
        'file_name',
        'path',
        'media_type',
        'extension'
    ];
    public $protectedColumns = [];
    public $row = [];

    function get(Request $request)
    {
        
        /*
        ::must_update:: servis izinleri nasıl kontrol ediliyor ?
        // start: check user get permission
        $directoryName = basename(dirname(__FILE__));
        $fileName = basename(__FILE__);
    
        includeLibrary('adminlte/checkUserGetPermission');
        $permissionResult = checkUserGetPermission($directoryName, $fileName);
    
        if ($permissionResult['error']) {
            $controller->errorCount = 1;
            $controller->lastError = $permissionResult['error_msg'];
            return false;
        }
        // end: check user get permission
        */
 
        $list = array();
        
        $parameters = $request->route()->parameters();

        $model = '';
        if (!isset($parameters['model'])) {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->list = $list;
            $objectHTMLDB->columns = $this->columns;
            $objectHTMLDB->printHTMLDBList();
            return;
        } // if (!isset($parameters['model'])) {
            
        $object_id = '';
        if (!isset($parameters['object_id'])) {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->list = $list;
            $objectHTMLDB->columns = $this->columns;
            $objectHTMLDB->printHTMLDBList();
            return;
        } // if (!isset($parameters['object_id'])) {

        $model = htmlspecialchars($parameters['model']);
        $object_id = intval($parameters['object_id']);

        if (0 == $object_id) {
            $objectHTMLDB = new HTMLDB();
            $objectHTMLDB->list = $list;
            $objectHTMLDB->columns = $this->columns;
            $objectHTMLDB->printHTMLDBList();
            return;
        } // if (0 == $object_id) {
    
        $tablename = strtolower($model) . "__filetable";
        
        $list = array();
        $index = 0;
    
        try {
			$connection = DB::connection()->getPdo();
		} catch (PDOException $e) {
			print($e->getMessage());
        }
                
        $selectSQL = "SELECT * FROM `". $tablename . "` WHERE `object_id`=:object_id ORDER BY file_index;";
        $objPDO = $connection->prepare($selectSQL);
        $objPDO->bindParam(':object_id', $object_id, PDO::PARAM_INT);
        
        $objPDO->execute();
        $data = $objPDO->fetchAll();
    
        foreach($data as $row) {
            $list[$index]["id"] = $row["id"];
            $list[$index]["object_property"] = $row["object_property"];
            $list[$index]["file_name"] = $row["file_name"];
            $list[$index]["path"] = $row["path"];
            $list[$index]["media_type"] = $row["media_type"];
    
            $fileNameTokens = explode('.', $row["file_name"]);
            $list[$index]["extension"] = strtolower(end($fileNameTokens));
    
            $index++;
        }

        $objectHTMLDB = new HTMLDB();
        $objectHTMLDB->list = $list;
        $objectHTMLDB->columns = $this->columns;
        $objectHTMLDB->printHTMLDBList();
        return;
    }
}
