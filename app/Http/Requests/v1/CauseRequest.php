<?php

namespace App\Http\Requests\v1;

use App\Http\Requests\Request;

class CauseRequest extends Request
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
            'name' => 'required|max:50',
            'short_desc' => 'max:255',
            'active' => 'boolean',
            'upload_id' => 'exists:uploads,id'
        ];
    }
}
