<?php

namespace App\Http\Requests\AdminLTE\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;

class AdminLTEUserPOSTRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $username_unique = 'unique:App\AdminLTE\AdminLTEUser,username';
        $email_unique = 'unique:App\AdminLTE\AdminLTEUser,email';

        $id = intval($this->input('id'));
        if ($id > 0) {
            $username_unique = $username_unique . ',' . $id;
            $email_unique = $email_unique . ',' . $id;
        }

        return [
            'username' => 'required|' . $username_unique,
            'email' => 'required|email|' . $email_unique
        ];
    }

}