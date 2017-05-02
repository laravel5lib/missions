<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class RequirementConditionRequest extends FormRequest
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
            'type' => 'required|string|in:'.implode(config('requirements.conditions.types'), ','),
            'operator' => 'required|string|in:'.implode(config('requirements.conditions.operators'), ','),
            'applies_to' => 'required|array'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'type' => 'sometimes|required|string|in:'.implode(config('requirements.conditions.types'), ','),
                'operator' => 'sometimes|required|string|in:'.implode(config('requirements.conditions.operators'), ','),
                'applies_to' => 'sometimes|required|array'
            ];
        }

        return $rules;
    }
}
