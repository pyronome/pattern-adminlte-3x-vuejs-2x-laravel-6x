<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;
use App\Http\Requests\AdminLTE\API\RegisterPOSTRequest;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{

    public function get_brand_data() {
        $objectAdminLTE = new AdminLTE();
        return $objectAdminLTE->getBrandData();
    }

    public function post(RegisterPOSTRequest $request)
    {
        /* {{@snippet:begin_login_post}} */

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $objectAdminLTEUser = new AdminLTEUser();
        $objectAdminLTEUser->created_by = 0;
        $objectAdminLTEUser->updated_by = 0;
        $objectAdminLTEUser->deleted = 0;
        $objectAdminLTEUser->enabled = 0;
        $objectAdminLTEUser->fullname = $request->input('fullname');
        $objectAdminLTEUser->username = $request->input('username');
        $objectAdminLTEUser->email = $request->input('email');
        $objectAdminLTEUser->password = bcrypt($request->input('password'));
        $objectAdminLTEUser->adminlteusergroup_id = 0;
		$objectAdminLTEUser->save();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return [
            'landing_page' => 'login'
        ];
        
         /* {{@snippet:end_login_post}} */
    }

}