<?php

namespace App\Http\Controllers\AdminLTE\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\AdminLTE;
use App\AdminLTEUser;
use App\Http\Requests\AdminLTE\API\LoginPOSTRequest;

class LoginController extends Controller
{

    public function post(LoginPOSTRequest $request)
    {
        $adminLTEUser = AdminLTEUser::where(
                'email',
                $request->input('email'))
                ->first();

        auth()->guard('adminlteuser')->login($adminLTEUser);
    }

}
