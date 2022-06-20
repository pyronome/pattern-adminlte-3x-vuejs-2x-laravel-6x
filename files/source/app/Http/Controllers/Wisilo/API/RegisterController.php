<?php

namespace App\Http\Controllers\Wisilo\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;
use App\Http\Requests\Wisilo\API\RegisterPOSTRequest;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{

    public function get_brand_data() {
        $objectWisilo = new Wisilo();
        return $objectWisilo->getBrandData();
    }

    public function post(RegisterPOSTRequest $request)
    {
        /* {{@snippet:begin_login_post}} */

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $objectWisiloUser = new WisiloUser();
        $objectWisiloUser->created_by = 0;
        $objectWisiloUser->updated_by = 0;
        $objectWisiloUser->deleted = 0;
        $objectWisiloUser->enabled = 0;
        $objectWisiloUser->fullname = $request->input('fullname');
        $objectWisiloUser->username = $request->input('username');
        $objectWisiloUser->email = $request->input('email');
        $objectWisiloUser->password = bcrypt($request->input('password'));
        $objectWisiloUser->wisilousergroup_id = 0;
		$objectWisiloUser->save();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        return [
            'landing_page' => 'login'
        ];
        
         /* {{@snippet:end_login_post}} */
    }

}