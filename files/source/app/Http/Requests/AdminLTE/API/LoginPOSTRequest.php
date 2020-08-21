<?php

namespace App\Http\Requests\AdminLTE\API;

use Illuminate\Foundation\Http\FormRequest;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUser;

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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $adminLTEUser = AdminLTEUser::where('email', $this->email)
                    ->first();
            $confirmed = false;
            if ($adminLTEUser != null)
            {
                if (password_verify($this->password, $adminLTEUser->password))
                {
                    $confirmed = true;
                } // if (password_verify($this->password, $adminLTEUser->password))
            } // if (null == $adminLTEUser)
            if (!$confirmed)
            {
                $validator->errors()->add(
                        'password',
                        'Your e-mail address or password is not correct. Please check and try again.');
                sleep(2);
            } // if (!$confirmed)
        });
        return;
    }

}
