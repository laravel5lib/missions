<?php

namespace App\Http\Requests\v1;

use App\Utilities\v1\Country;
use Dingo\Api\Http\FormRequest;

class ProjectTypeRequest extends FormRequest
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
            'name'                            => 'required|max:50',
            'country_code'                    => 'required|in:' . Country::codes(),
            'short_desc'                      => 'required|max:255',
            'upload_id'                       => 'exists:uploads,id',
            'active'                          => 'boolean',
            'costs'                           => 'sometimes|required|array',
            'costs.*.name'                    => 'required|string',
            'costs.*.description'             => 'string',
            'costs.*.active_at'               => 'required|date',
            'costs.*.amount'                  => 'required|numeric',
            'costs.*.type'                    => 'required|string',
            'costs.*.payments'                => 'sometimes|required|array',
            'costs.*.payments.*.amount_owed'  => 'required|numeric',
            'costs.*.payments.*.percent_owed' => 'required|numeric',
            'costs.*.payments.*.due_at'       => 'date',
            'costs.*.payments.*.upfront'      => 'boolean',
            'costs.*.payments.*.grace_period' => 'required|numeric',
        ];
    }
}
