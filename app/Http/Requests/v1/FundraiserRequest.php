<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class FundraiserRequest extends FormRequest
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
            'name'             => 'required|string',
            'fund_id'          => 'required|exists:funds,id',
            'goal_amount'      => 'required|min:1|numeric',
            'description'      => 'string',
            'banner_upload_id' => 'string|exists:uploads,id',
            'upload_ids'       => 'array',
            'upload_ids.*'     => 'required|exists:uploads,id',
            'started_at'       => 'required|date|before:ended_at',
            'ended_at'         => 'required|date|after:started_at'
        ];
    }
}
