<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\Http\Requests\AdminLTE\API\RegisterPOSTRequest;
use App\AdminLTE\AdminLTEUserLayout;

class RegisterController extends Controller
{

    public function get_brand_data() {
        $objectAdminLTE = new AdminLTE();
        return $objectAdminLTE->getBrandData();
    }

    public function post(RegisterPOSTRequest $request)
    {
        /* {{@snippet:begin_login_post}} */

        $objectAdminLTEUser = new AdminLTEUser();
        $objectAdminLTEUser->created_by = 0;
        $objectAdminLTEUser->updated_by = 0;
        $objectAdminLTEUser->deleted = 0;
        $objectAdminLTEUser->enabled = 0;
        $objectAdminLTEUser->fullname = $request->input('fullname');
        $objectAdminLTEUser->username = $request->input('username');
        $objectAdminLTEUser->email = $request->input('email');
        $objectAdminLTEUser->password = bcrypt($request->input('password'));
        $objectAdminLTEUser->adminlteusergroup_id = 2;//default seçtirebiliriz
		$objectAdminLTEUser->save();

        $baseUser = AdminLTEUser::where('adminlteusergroup_id', 2)
                ->where('deleted', 0)
                ->where('enabled', 1)
                ->first();

        if (null !== $baseUser) {
            $layoutList = AdminLTEUserLayout::where('deleted', false)->where('adminlteuser_id', $baseUser->id)->get();
            foreach ($layoutList as $layout) {
                $newLayout = new AdminLTEUserLayout();
                
                $newLayout->created_at = time();
                $newLayout->updated_at = time();
                $newLayout->adminlteuser_id = $objectAdminLTEUser->id;
                $newLayout->pagename = $layout->pagename;
                $newLayout->widgets = $layout->widgets;
                $newLayout->save();
            } // foreach ($objectList as $object)
        }

        return [
            'landing_page' => 'login'
        ];
        
         /* {{@snippet:end_login_post}} */
    }

}