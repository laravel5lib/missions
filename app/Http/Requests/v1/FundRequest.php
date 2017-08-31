<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class FundRequest extends FormRequest
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
            'name'          => 'required|string|unique:funds,name',
            'balance'       => 'integer',
            'class' => 'required|string',
            'item' => 'required|string',
            'fundable_id'   => 'required|string',
            'fundable_type' => 'required|string|in:reservations,trips,groups,campaigns,causes,project',
            'tags'          => 'array',
            'slug'           => 'unique:funds,slug'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'name'          => 'sometimes|required|string|unique:funds,name,' . $this->route('fund'),
                'balance'       => 'integer',
                'slug'          => 'sometimes|required|unique:funds,slug',
                'class'         => 'sometimes|required|string',
                'item'          => 'sometimes|required|string',
                'fundable_id'   => 'sometimes|required|string',
                'fundable_type' => 'sometimes|required|string|in:reservations,trips,groups,campaigns,causes,projects',
                'tags'          => 'array',
                'slug'          => 'unique:funds,slug,' . $this->route('fund')
            ];
        }

        return $rules;
    }
}
