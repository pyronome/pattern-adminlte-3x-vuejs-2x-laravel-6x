<?php

namespace App\Http\Requests\Wisilo\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Wisilo\Wisilo;
use App\Wisilo\WisiloUser;

class WidgetConfigPOSTRequest extends FormRequest
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
            /* 'email_type' => 'required|integer',
            'email_from_name' => 'required|string',
            'email_reply_to' => 'required|string',
            'email_smtp_host' => Rule::requiredIf($this->email_type > 0),
            'email_smtp_user' => Rule::requiredIf($this->email_type > 0),
            'email_smtp_password' => Rule::requiredIf($this->email_type > 0),
            'email_smtp_port' => Rule::requiredIf($this->email_type > 0) */
        ];
    }

}
