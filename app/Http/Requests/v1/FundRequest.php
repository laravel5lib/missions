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
        return [
            'name'          => 'required|string|unique',
            'balance'       => 'integer',
            'fundable_id'   => 'required|string',
            'fundable_type' => 'required|string|in:reservations,trips,groups,campaigns,causes,project',
            'tags'          => 'array',
        ];
    }
}
