<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class TodoRequest extends FormRequest
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
        $rules = [
            'task' => 'required|string|max:120',
            'todoable_type' => 'required|string',
            'todoable_id' => 'required|string',
            'complete' => 'boolean',
            'completed_at' => 'nullable|date',
            'user_id' => 'required_if:complete,true|string|exists:users,id'
        ];

        if ($this->isMethod('put')) {
            $rules['task'] = 'sometimes|required|string|max:120';
            $rules['todoable_type'] = 'sometimes|required|string';
            $rules['todoable_id'] = 'sometimes|required|string';
        }

        return $rules;
    }
}
