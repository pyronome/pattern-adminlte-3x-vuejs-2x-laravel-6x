<?php

namespace App\Http\Controllers\AdminLTE;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use Storage;

class MediaController extends Controller
{

    public $controllerName = 'media';

    public function index(Request $request)
    {
        $viewName = 'adminlte.' . $this->controllerName;

        if (view()->exists('adminlte.custom.' . $this->controllerName))
        {
            $viewName = 'adminlte.custom.' . $this->controllerName;
        } // if (view()->exists('adminlte.custom.' . $this->controllerName))

        $objectAdminLTE = new AdminLTE();

        $viewData['controllerName'] = $this->controllerName;
        $viewData['user'] = $objectAdminLTE->getUserData();

        return view($viewName, $viewData);
    }

    public function formupload(Request $request)
    {   

        /* ::must_update:: need validation */

        $target = isset($request['target'])
            ? htmlspecialchars($request['target'])
            : '';

        $target_path = 'public/' . strtolower($target);

        $media_type = isset($request['media_type'])
            ? intval($request['media_type'])
            : 1;


        $file = $request->file('file');
        $fileOriginalName = $file->getClientOriginalName();
        $extension = $file->extension();

        $fileRealName = str_replace('.' . $extension, '', $fileOriginalName);

        $objectAdminLTE = new AdminLTE();
        $filename = $objectAdminLTE->convertNameToFileName($fileRealName) . '_' . time()  . '.' . $extension;
        
        $path = Storage::putFileAs($target_path, $file, $filename);

        $real_path = str_replace('public/', '', $path);

        $lastInsertedId = $objectAdminLTE->insertModelPropertyFile($target, $media_type, $filename, $real_path);

        $lastMessage = $lastInsertedId . '#' . $filename . '#' . $real_path;

        return response()->json(['lastMessage'=>$lastMessage, 'errorCount'=>0, 'lastError'=>'']);
    }

}
