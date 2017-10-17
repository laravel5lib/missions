<?php

namespace App\Http\Requests\v1\Interaction;

use Dingo\Api\Http\FormRequest;

class DecisionRequest extends FormRequest
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
            'id'             => 'string|size:36',
            'author_id'      => 'required|string|size:36',
            'author_type'    => 'required|in:reservations,translators',
            'region_id'      => 'required|exists:regions,id',
            'name'           => 'string',
            'gender'         => 'required|string|in:male,female',
            'age_group'      => 'required|string|in:child,youth,adult',
            'decision'       => 'required|boolean',
            'phone'          => 'string',
            'email'          => 'string|email',
            'lat'            => 'string',
            'long'           => 'string',
            'created_at'     => 'date',
            'updated_at'     => 'date'
        ];
    }
}
