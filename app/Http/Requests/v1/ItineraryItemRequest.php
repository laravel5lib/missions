<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class ItineraryItemRequest extends FormRequest
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
            'title' => 'required|string',
            'description' => 'string',
            'type' => 'required|in:travel,event,accomodation',
            'occurs_at' => 'required|date'
        ];

        if ($this->isMethod('PUT')) {
            $rules['title'] = 'sometimes|required|string';
            $rules['type'] = 'sometimes|required|in:travel,event,accomodation';
            $rules['occurs_at'] = 'sometimes|required|date';
            $rules['attachment'] = 'array';
            $rules['attachment.*.type'] = 'required|in:transports';
            $rules['attachment.*.id'] = 'required|string';
        }

        return $rules;
    }
}
