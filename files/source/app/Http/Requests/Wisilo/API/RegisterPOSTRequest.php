<?php

namespace App\Http\Requests\Wisilo\API;

use Illuminate\Foundation\Http\FormRequest;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;

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
            'username' => 'required|string|unique:wisilousertable,username',
            'email' => 'required|string|unique:wisilousertable,email',
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
            $objectWisilo = new Wisilo();

            if (!$objectWisilo->validateEmailAddress($this->email))
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
