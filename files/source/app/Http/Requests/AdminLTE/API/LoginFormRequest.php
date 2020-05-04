<?php

namespace App\Http\Requests\AdminLTE\API;

use Illuminate\Foundation\Http\FormRequest;

class LoginFormRequest extends FormRequest
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
            'email' => 'required|string|email',
            'password' => 'required|required_with:password_confirmation|string|confirmed'
        ];
    }

    public function withValidator($validator)
    {
        // checks user current password
        // before making changes
        $validator->after(function ($validator) {

        $adminLTEUser = AdminLTEUser::where('email', $request['email'])
                ->first();
            
        $confirmed = false;

        if ($adminLTEUser != null)
        {
            if (password_verify($this->row['password'], $adminLTEUser->password))
            {
                $confirmed = true;
            }
        } // if (null == $adminLTEUser)

        if (!$confirmed)
        {
            $result['errorCount']++;
            if ($result['lastError'] != '') {
                $result['lastError'] .= '<br>';
            } // if ($result['lastError'] != '') {

            $result['lastError'] .= __('Your e-mail address or password is not correct. Please check and try again.');

            sleep(2);
        } // if (!$confirmed)


            if ( !Hash::check($this->current_password, $this->user()->password) ) {
                $validator->errors()->add('current_password', 'Your current password is incorrect.');
            }
        });
        return;
    }

}
