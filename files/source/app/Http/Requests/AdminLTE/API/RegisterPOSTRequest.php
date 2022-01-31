<?php

namespace App\Http\Requests\AdminLTE\API;

use Illuminate\Foundation\Http\FormRequest;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;

class RegisterPOSTRequest extends FormRequest
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
        return [
            'username' => 'required|string|unique:adminlteusertable,username',
            'email' => 'required|string|unique:adminlteusertable,email',
            'password' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'username.required' => __('Please enter your username address.'),
            'username.unique' => __('This username in use. Please try another.'),
            'email.required' => __('Please enter your email address.'),
            'email.unique' => __('This email address in use. Please try another.'),
            'password.required' => __('Please enter your password.'),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $objectAdminLTE = new AdminLTE();

            if (!$objectAdminLTE->validateEmailAddress($this->email))
            {
                $validator->errors()->add(
                    'email',
                    __('Please enter valid email adress.'));
                
                sleep(2);
            } // if (!$confirmed)
        });
        return;
    }

}
