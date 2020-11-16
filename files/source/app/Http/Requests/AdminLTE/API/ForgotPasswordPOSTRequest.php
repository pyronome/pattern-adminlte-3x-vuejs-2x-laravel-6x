<?php

namespace App\Http\Requests\AdminLTE\API;

use Illuminate\Foundation\Http\FormRequest;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;

class ForgotPasswordPOSTRequest extends FormRequest
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
            'email' => 'required|string|email'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('Please enter your email address.'),
            'email.email' => __('You have entered an invalid email address.'),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $adminLTEUser = AdminLTEUser::where('email', $this->email)
                    ->first();
            if (null == $adminLTEUser)
            {
                $validator->errors()->add(
                        'email',
                        __('Your email address is not recognized. Please check your email address and try again.'));
                sleep(2);
            } // if (null == $adminLTEUser)
        });
        return;
    }
}
