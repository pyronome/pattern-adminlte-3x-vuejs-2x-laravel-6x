<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEModelOption;
use App\AdminLTE\AdminLTEUser;
use App\Http\Requests\AdminLTE\API\AdminLTEUserPOSTRequest;

class AdminLTEUserController extends Controller
{
	public function get(Request $request)
    {    
        $list = [];
        
        $parameters = $request->route()->parameters();

        $id = isset($parameters['id'])
            ? intval($parameters['id'])
            : 0;


        if (0 == $id) {
            return;
        } // if (!isset($parameters['id'])) {
        
        // is new ?
        if ('new' == htmlspecialchars($parameters['id'])) {
            return;
        } // if (isset($parameters['id']) && ('new' == htmlspecialchars($parameters['id']))) {

        if ($id > 0) {
            $objectAdminLTEUserList[] = AdminLTEUser::where('id', $id)->first();
        }

        $objectAdminLTE = new AdminLTE();
        $objectAdminLTEUser = NULL;
        $index = 0;

        foreach ($objectAdminLTEUserList as $objectAdminLTEUser)
        {
            $displayTexts = $objectAdminLTE->getObjectDisplayTexts('AdminLTEUser', $objectAdminLTEUser);

            $list[$index]['id'] = $objectAdminLTEUser->id;
            $list[$index]['id__displaytext__'] = $displayTexts['id'];
            $list[$index]['deleted'] = $objectAdminLTEUser->deleted;
            $list[$index]['deleted__displaytext__'] = $displayTexts['deleted'];
            $list[$index]['created_at'] = $objectAdminLTEUser->created_at;
            $list[$index]['created_at__displaytext__'] = $displayTexts['created_at'];
            $list[$index]['updated_at'] = $objectAdminLTEUser->updated_at;
            $list[$index]['updated_at__displaytext__'] = $displayTexts['updated_at'];
            $list[$index]['enabled'] = $objectAdminLTEUser->enabled;
            $list[$index]['enabled__displaytext__'] = $displayTexts['enabled'];
            $list[$index]['adminlteusergroup_id'] = array($objectAdminLTEUser->adminlteusergroup_id);
            $list[$index]['adminlteusergroup_id__displaytext__'] = $displayTexts['adminlteusergroup_id'];
            $list[$index]['fullname'] = $objectAdminLTEUser->fullname;
            $list[$index]['fullname__displaytext__'] = $displayTexts['fullname'];
            $list[$index]['username'] = $objectAdminLTEUser->username;
            $list[$index]['username__displaytext__'] = $displayTexts['username'];
            $list[$index]['email'] = $objectAdminLTEUser->email;
            $list[$index]['email__displaytext__'] = $displayTexts['email'];
            $list[$index]['password'] = '';
            $list[$index]['password__displaytext__'] = '******';

			$external_ids = array();
            foreach ($objectAdminLTE->get_model_files_by_property('AdminLTEUser', $objectAdminLTEUser->id, 'profile_img') as $fileData) {
                $external_ids[] = $fileData['id'];
            }

            if(empty($external_ids)){
                $current_external_value = '';
            } else {
                $current_external_value = implode(',', $external_ids);
            }

            $list[$index]['profile_img'] = $current_external_value;
            $list[$index]['profile_img__displaytext__'] = $displayTexts['profile_img'];
   
            $index++;
        } // foreach ($objectAdminLTEUserList as $objectAdminLTEUser)

        return [
            'list' => $list
        ];
    }

	public function post(AdminLTEUserPOSTRequest $request)
    {
        $id = intval($request->input('id'));

        if ($id > 0) {
            $objectAdminLTEUser = AdminLTEUser::find($id);
        } else {
            $objectAdminLTEUser = new AdminLTEUser();
        } // if ($id > 0) {
        
        $objectAdminLTEUser->deleted = 0;
        
        $objectAdminLTEUser->enabled = ('' != $request->input('enabled'))
            ? intval($request->input('enabled'))
            : 0;

        if ('' != $request->input('adminlteusergroup_id')) {
		    $objectAdminLTEUser->adminlteusergroup_id = intval($request->input('adminlteusergroup_id'));
        }

        $objectAdminLTEUser->fullname = $request->input('fullname');
        
        $objectAdminLTEUser->username = $request->input('username');

        $objectAdminLTEUser->email = $request->input('email');
        
        if ('' != $request->input('password')) {
            $objectAdminLTEUser->password = bcrypt($request->input('password'));
        } 

        $profile_img = $request->input('profile_img');

        $objectAdminLTEUser->save();
        
		$objectAdminLTE = new AdminLTE();
		$objectAdminLTE->updateModelFileObject('AdminLTEUser', $objectAdminLTEUser->id, 'profile_img', $profile_img);
 
        return [
            'id' => $objectAdminLTEUser->id
        ];
    }

    public function delete(Request $request)
    {
        $selected_ids = $request->input('selected_ids');
        
        $objects = AdminLTEUser::where('deleted', false)
                ->whereIn('id', $selected_ids)
                ->get();

        foreach ($objects as $object)
        {
            $object->deleted = 1;
            $object->save();                
        } // foreach ($objects as $object)

        return ['message' => "Success"];
    }

	public function get_files(Request $request) {
        $list = [];
        
        $parameters = $request->route()->parameters();
        
        if (!isset($parameters['id'])) {
            return;
        } // if (!isset($parameters['id'])) {

        $object_id = intval($parameters['id']);

        if (0 == $object_id) {
            return;
        } // if (!isset($parameters['id'])) {
        
        $objectAdminLTE = new AdminLTE();
        $files = $objectAdminLTE->get_model_files('AdminLTEUser', $object_id);
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

        return [
            'list' => $list
        ];
    }
}