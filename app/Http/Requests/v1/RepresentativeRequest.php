<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class RepresentativeRequest extends FormRequest
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
            'name' => 'required|string|unique:representatives,name',
            'email' => 'string|nullable',
            'phone' => 'string|nullable',
            'ext' => 'string|nullable'
        ];
    }
}
