<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class CreateFormSubmissionRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'content' => 'required|array',
            'content.*.id' => 'required|string',
            'content.*.label' => 'required|string',
            'content.*.description' => 'nullable|string',
            'content.*.input' => 'required|array',
            'content.*.input.type' => 'required|string',
            'content.*.input.placeholder' => 'nullable|string',
            'content.*.input.options' => 'nullable|array',
            'content.*.input.value' => 'nullable',
            'helptext' => 'nullable|string',
            'rules' => 'nullable|array'
        ];
    }
}
