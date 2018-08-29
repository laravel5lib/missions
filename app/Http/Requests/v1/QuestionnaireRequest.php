<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class QuestionnaireRequest extends FormRequest
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
        if ($this->isMethod('post')) {
            return [
                'content' => 'required|array',
                'reservation_id' => 'required|exists:reservations,id'
            ];
        }

        return [
            'content' => 'sometimes|required|array',
            'reservation_id' => 'sometimes|required|exists:reservations,id'
        ]; 
    }
}
