<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEModelOption;
use App\AdminLTE\AdminLTEUserGroup;
use App\Http\Requests\AdminLTE\API\AdminLTEUserGroupPOSTRequest;

class AdminLTEUserGroupController extends Controller
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
            $objectAdminLTEUserGroupList[] = AdminLTEUserGroup::where('id', $id)->first();
        }

        $objectAdminLTE = new AdminLTE();
        $objectAdminLTEUserGroup = NULL;
        $index = 0;

        foreach ($objectAdminLTEUserGroupList as $objectAdminLTEUserGroup)
        {
            $displayTexts = $objectAdminLTE->getObjectDisplayTexts('AdminLTEUserGroup', $objectAdminLTEUserGroup);

            $list[$index]['id'] = $objectAdminLTEUserGroup->id;
            $list[$index]['id__displaytext__'] = $displayTexts['id'];
            $list[$index]['deleted'] = $objectAdminLTEUserGroup->deleted;
            $list[$index]['deleted__displaytext__'] = $displayTexts['deleted'];
            $list[$index]['created_at'] = $objectAdminLTEUserGroup->created_at;
            $list[$index]['created_at__displaytext__'] = $displayTexts['created_at'];
            $list[$index]['updated_at'] = $objectAdminLTEUserGroup->updated_at;
            $list[$index]['updated_at__displaytext__'] = $displayTexts['updated_at'];
            $list[$index]['enabled'] = $objectAdminLTEUserGroup->enabled;
            $list[$index]['enabled__displaytext__'] = $displayTexts['enabled'];
            $list[$index]['title'] = $objectAdminLTEUserGroup->title;
            $list[$index]['title__displaytext__'] = $displayTexts['title'];
            $list[$index]['widget_permission'] = $objectAdminLTEUserGroup->widget_permission;
            $list[$index]['widget_permission__displaytext__'] = $displayTexts['widget_permission'];

            $index++;
        } // foreach ($objectAdminLTEUserGroupList as $objectAdminLTEUserGroup)

        return [
            'list' => $list
        ];
    }

	public function post(AdminLTEUserGroupPOSTRequest $request)
    {
        $id = intval($request->input('id'));

        if ($id > 0) {
            $objectAdminLTEUserGroup = AdminLTEUserGroup::find($id);
        } else {
            $objectAdminLTEUserGroup = new AdminLTEUserGroup();
        } // if ($id > 0) {
        
        $objectAdminLTEUserGroup->deleted = 0;
        $objectAdminLTEUserGroup->enabled = ('' != $request->input('enabled'))
                ? intval($request->input('enabled'))
                : 0; 
        $objectAdminLTEUserGroup->title = $request->input('title');
        $objectAdminLTEUserGroup->widget_permission = ('' != $request->input('widget_permission'))
                ? intval($request->input('widget_permission'))
                : 0;

        $objectAdminLTEUserGroup->save();
        
        return [
            'id' => $objectAdminLTEUserGroup->id
        ];
    }

    public function delete(Request $request)
    {
        $selected_ids = $request->input('selected_ids');
        
        $objects = AdminLTEUserGroup::where('deleted', false)
                ->whereIn('id', $selected_ids)
                ->get();

        foreach ($objects as $object)
        {
            $object->deleted = 1;
            $object->save();                
        } // foreach ($objects as $object)

        return ['message' => "Success"];
    }
}