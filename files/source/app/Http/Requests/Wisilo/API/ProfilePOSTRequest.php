<?php

namespace App\Http\Requests\Wisilo\API;

use Illuminate\Foundation\Http\FormRequest;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;

class ProfilePOSTRequest extends FormRequest
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
            'fullname' => 'required|string',
            'username' => 'required|string',
            'email' => 'required|string|email',
            'password0' => 'nullable',
            'password1' => 'nullable',
            'password2' => 'nullable'
        ];
    }

    public function messages()
    {
        return [
            'fullname.required' => __('Please enter your full name.'),
            'username.required' => __('Please enter your user name.'),
            'email.required' => __('Please enter your email address.'),
            'email.email' => __('You have entered an invalid email address.'),
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            $wisilo = new Wisilo();
            $userData = $wisilo->getUserData();

            $otherUser = WisiloUser::where('deleted', false)
                    ->where('username', $this->username)
                    ->where('id', '!=', $userData['id'])
                    ->first();

            if ($otherUser != null)
            {
               $validator->errors()->add(
                        'username',
                        __('Username specified belongs to another user. Please specify another username.'));
                sleep(1);
            } // if ($otherUser != null)

            $otherUser = WisiloUser::where('deleted', false)
                    ->where('email', $this->email)
                    ->where('id', '!=', $userData['id'])
                    ->first();

            if ($otherUser != null)
            {
                $validator->errors()->add(
                        'email',
                        __('E-mail address specified belongs to another user. Please specify another e-mail address.'));
                sleep(1);
            } // if ($otherUser != null)

            if (($this->password0 != null)
                    || ($this->password1 != null)
                    || ($this->password2 != null)) {
                $currentUser = WisiloUser::find($userData['id']);
                if ($this->password0 == null) {
                    $validator->errors()->add(
                            'password0',
                            __('Please specify your current password.'));
                    sleep(1);
                } else if (!password_verify($this->password0,
                        $currentUser->password)) {
                    $validator->errors()->add(
                            'password0',
                            __('Your current password is wrong.'));
                    sleep(1);
                } else {
                    if ('' == $this->password1)
                    {
                        $validator->errors()->add(
                                'password1',
                                __('Please specify your new password.'));
                    }
                    else if ('' == $this->password2)
                    {
                        $validator->errors()->add(
                                'password2',
                                __('Please re-enter your new password.'));
                    }
                    else if ($this->password1 != $this->password2)
                    {
                        $validator->errors()->add(
                                'password1',
                                __('Your new passwords are not matched. Please check your new passwords and try again.'));
                    } // if ('' == $this->password1)
                } // if (!password_verify($this->password0,
            } // if (property_exists($this, 'password0')) {
        });
        return;
    }

}
