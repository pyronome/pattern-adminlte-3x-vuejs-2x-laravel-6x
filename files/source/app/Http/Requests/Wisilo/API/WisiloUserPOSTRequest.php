<?php

namespace App\Http\Requests\Wisilo\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;

class WisiloUserPOSTRequest extends FormRequest
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
        $username_unique = 'unique:App\Wisilo\WisiloUser,username';
        $email_unique = 'unique:App\Wisilo\WisiloUser,email';

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