<?php

namespace App\Http\Requests\Wisilo\API;

use Illuminate\Foundation\Http\FormRequest;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;

class LoginPOSTRequest extends FormRequest
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
            'email' => 'required|string',
            'password' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => __('Please enter your email address.'),
            'password.required' => __('Please enter your password.'),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $wisiloUser = WisiloUser::where('email', $this->email)
                    ->first();
            $confirmed = false;
            $enabled = true;
            if ($wisiloUser != null)
            {
                if(1 == $wisiloUser->enabled) {
                    if (password_verify($this->password, $wisiloUser->password))
                    {
                        $confirmed = true;
                    } // if (password_verify($this->password, $wisiloUser->password))
                } else {
                    $enabled = false;
                }
            } // if (null == $wisiloUser)

            if (!$confirmed)
            {
                if(!$enabled) {
                    $validator->errors()->add(
                        'enabled',
                        __('This account has been disabled. Please contact with system administrator.'));
                } else {
                    $validator->errors()->add(
                        'password',
                        __('Your e-mail address or password is not correct. Please check and try again.'));
                }
                
                sleep(2);
            } // if (!$confirmed)
        });
        return;
    }

}
