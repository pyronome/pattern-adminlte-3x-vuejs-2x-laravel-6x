<?php

namespace App\Http\Requests\AdminLTE\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\AdminLTE\AdminLTE;
use App\AdminLTE\AdminLTEUserGroup;

class AdminLTEUserGroupPOSTRequest extends FormRequest
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
        $title_unique = 'unique:App\AdminLTE\AdminLTEUserGroup,title';
        $id = intval($this->input('id'));
        if ($id > 0) {
            $title_unique = $title_unique . ',' . $id;
        }

        return [
			'title' => 'required|' . $title_unique
        ];
    }

}