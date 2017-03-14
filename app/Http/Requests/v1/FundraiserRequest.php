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
        $rules = [
            'name'             => 'required|string',
            'type'             => 'string|in:general',
            'url'              => 'string|unique:fundraisers,url',
            'fund_id'          => 'required|exists:funds,id',
            'public'           => 'boolean',
            'description'      => 'string',
            'banner_upload_id' => 'string|exists:uploads,id',
            'upload_ids'       => 'array',
            'upload_ids.*'     => 'required|exists:uploads,id',
            'started_at'       => 'required|date|before:ended_at',
            'ended_at'         => 'required|date|after:started_at',
            'tags'             => 'array'
        ];

        if ($this->isMethod('put')) {
            $rules['url'] = 'string|unique:fundraisers,url,' . $this->route('fundraisers');
            $rules['name'] = 'sometimes|required|string';
            $rules['fund_id'] = 'sometimes|required|string';
            $rules['started_at'] = 'sometimes|required|date|before:ended_at';
            $rules['ended_at'] = 'sometimes|required|date|after:started_at';
        }

        return $rules;
    }
}
