<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class ExportRequest extends FormRequest
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
            'fields' => 'bail|required|array',
            'filename' => 'string',
            'author_id' => 'bail|exists:users,id'
        ];
    }

    /**
     * Get the validation error messages that apply to the rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'fields.required' => 'At least one field is required.',
            'users.exists' => 'An author with that user id does not exist.'
        ];
    }
}
