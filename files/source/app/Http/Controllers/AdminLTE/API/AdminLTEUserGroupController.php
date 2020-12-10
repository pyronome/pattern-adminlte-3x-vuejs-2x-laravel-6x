<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEModelOption;
use App\AdminLTE\AdminLTEUserGroup;
use App\AdminLTE\AdminLTEPermission;
use App\Http\Requests\AdminLTE\API\AdminLTEUserGroupPOSTRequest;

class AdminLTEUserGroupController extends Controller
{
    public function get(Request $request)
    {    
        $data = [];
        
        $parameters = $request->route()->parameters();

        $id = isset($parameters['id'])
            ? intval($parameters['id'])
            : 0;

        if ($id <= 0) {
            return;
        } // if ($id <= 0) {
        
        // is new ?
        if ('new' == htmlspecialchars($parameters['id'])) {
            return;
        } // if ('new' == htmlspecialchars($parameters['id'])) {

        if ($id > 0) {
            $objectAdminLTEUserGroup = AdminLTEUserGroup::where('id', $id)->where('deleted', 0)->first();
        }
        
        $User = auth()->guard('adminlteuser')->user();

        if ($User->can('viewAny', AdminLTEUserGroup::class) && $User->can('view', $objectAdminLTEUserGroup)) {
            $data['user_can_create'] = $User->can('create', AdminLTEUserGroup::class);
            $data['user_can_read'] = $User->can('view', $objectAdminLTEUserGroup);
            $data['user_can_update'] = $User->can('update', $objectAdminLTEUserGroup);
            $data['user_can_delete'] = $User->can('delete', $objectAdminLTEUserGroup);
            $data['user_can_view'] = $User->can('viewAny', $objectAdminLTEUserGroup);

            $objectAdminLTE = new AdminLTE();
            $displayTexts = $objectAdminLTE->getObjectDisplayTexts('AdminLTEUserGroup', $objectAdminLTEUserGroup);

            $data['id'] = $objectAdminLTEUserGroup->id;
            $data['id__displaytext__'] = $displayTexts['id'];
            $data['deleted'] = $objectAdminLTEUserGroup->deleted;
            $data['deleted__displaytext__'] = $displayTexts['deleted'];
            $data['created_at'] = $objectAdminLTEUserGroup->created_at;
            $data['created_at__displaytext__'] = $displayTexts['created_at'];
            $data['updated_at'] = $objectAdminLTEUserGroup->updated_at;
            $data['updated_at__displaytext__'] = $displayTexts['updated_at'];
            $data['enabled'] = $objectAdminLTEUserGroup->enabled;
            $data['enabled__displaytext__'] = $displayTexts['enabled'];
            $data['admin'] = $objectAdminLTEUserGroup->admin;
            $data['admin__displaytext__'] = $displayTexts['admin'];
            $data['title'] = $objectAdminLTEUserGroup->title;
            $data['title__displaytext__'] = $displayTexts['title'];
            $data['widget_permission'] = $objectAdminLTEUserGroup->widget_permission;
            $data['widget_permission__displaytext__'] = $displayTexts['widget_permission'];
        } // if (null !== $objectAdminLTEUserGroup) {

        return [
            'object' => $data
        ];
    }

    public function post(AdminLTEUserGroupPOSTRequest $request)
    {
        $User = auth()->guard('adminlteuser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $id = intval($request->input('id'));

        if ($id > 0) {
            $objectAdminLTEUserGroup = AdminLTEUserGroup::find($id);
            if (!$User->can('update', $objectAdminLTEUserGroup)) {
                $has_error = true;
                $error_msg = __('You can not update this object. Contact your system administrator for more information.');
            }
        } else {
            $objectAdminLTEUserGroup = new AdminLTEUserGroup();
            if (!$User->can('create', AdminLTEUserGroup::class)) {
                $has_error = true;
                $error_msg = __('You can not create any object. Contact your system administrator for more information.');
            }
        } // if ($id > 0) {
        
        if ($has_error) {
            $return_data['id'] = $id;
            $return_data['has_error'] = $has_error;
            $return_data['error_msg'] = $error_msg;

            return $return_data;
        }
        
        $objectAdminLTEUserGroup->deleted = 0;
        $objectAdminLTEUserGroup->enabled = ('' != $request->input('enabled'))
                ? intval($request->input('enabled'))
                : 0; 
        $objectAdminLTEUserGroup->admin = ('' != $request->input('admin'))
                ? intval($request->input('admin'))
                : 0;         
        $objectAdminLTEUserGroup->title = $request->input('title');
        $objectAdminLTEUserGroup->widget_permission = ('' != $request->input('widget_permission'))
                ? intval($request->input('widget_permission'))
                : 0;

        $objectAdminLTEUserGroup->save();
        
        $return_data['id'] = $objectAdminLTEUserGroup->id;
        $return_data['has_error'] = false;
        $return_data['error_msg'] = '';

        return $return_data;
    }

    public function delete(Request $request)
    {
        $User = auth()->guard('adminlteuser')->user();
        $has_error = false;
        $error_msg = '';
        $return_data = [];
        
        $selected_ids = $request->input('selected_ids');
        
        $objects = AdminLTEUserGroup::where('deleted', false)
                ->whereIn('id', $selected_ids)
                ->get();

        foreach ($objects as $object)
        {
            if (!$User->can('delete', $object)) {
                $has_error = true;
                $error_msg = __('You can not delete this object. Contact your system administrator for more information.');
                break;
            }              
        } // foreach ($objects as $object)

        if (!$has_error) {
            foreach ($objects as $object)
            {
                $object->deleted = 1;
                $object->save();                
            } // foreach ($objects as $object)
        }
            
        $return_data['has_error'] = true;
        $return_data['error_msg'] = $error_msg;

        return $return_data;
    }    

    public function get_layout_data(Request $request) {
        $form_data = [];
        
        $data = [];

        $parameters = $request->route()->parameters();

        $id = isset($parameters['id'])
            ? intval($parameters['id'])
            : 0;

        if ($id <= 0) {
            return;
        } // if (!isset($parameters['id'])) {

        $objectAdminLTEUserGroup = AdminLTEUserGroup::find($id);
        
        if (null !== $objectAdminLTEUserGroup) {
            $form_data['usergroup_id'] = $id;
            $form_data['title'] = $objectAdminLTEUserGroup->title;
            $form_data['selected_pages'] = array();
            $form_data['layout_data'] = '';        

            $objectAdminLTE = new AdminLTE();
            $objectAdminLTEMetas = $objectAdminLTE->getMetaData('__usergroup_layout', $id);

            if (count($objectAdminLTEMetas) > 0) {
                $objectAdminLTEMeta = $objectAdminLTEMetas[0];
                
                $metaData = json_decode(
                    $objectAdminLTE->base64Decode($objectAdminLTEMeta->meta_value),
                    (JSON_HEX_QUOT
                    | JSON_HEX_TAG
                    | JSON_HEX_AMP
                    | JSON_HEX_APOS));

                $iframeData = isset($metaData['iframes']) ? $metaData['iframes'] : [];

                $form_data['selected_pages'] = $iframeData['selected_pages'];
                $form_data['layout_data'] = $iframeData['values'];
            }
        }

        return [
            'form_data' => $form_data
        ];
    }

    public function post_layout_data(Request $request)
    {
        $usergroup_id = intval($request->input('usergroup_id'));
        $metaData = [];
        $metaData['iframes'] = array();
        $metaData['iframes']['selected_pages'] = $request->input('selected_pages');
        $metaData['iframes']['values'] = $request->input('layout_data');
        
        $objectAdminLTE = new AdminLTE();
        $encodedData = $objectAdminLTE->base64encode(json_encode($metaData, (JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS)));
        $success = $objectAdminLTE->setMetaData('__usergroup_layout', $usergroup_id, $encodedData);

        if ($success) {
            $return_data['id'] = $usergroup_id;
            $return_data['has_error'] = false;
            $return_data['error_msg'] = '';
        } else {
            $return_data['id'] = $usergroup_id;
            $return_data['has_error'] = true;
            $return_data['error_msg'] = __('An error occurred while processing your request.');
        }

        return $return_data;
    }
}