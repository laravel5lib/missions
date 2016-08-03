<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class CampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $required = [
            'name'         => 'required|max:100',
            'country_code' => 'required|string',
            'started_at'   => 'required|date',
            'ended_at'     => 'required|date',
            'page_url'     => 'required_with:published_at|string|unique:campaigns,page_url'
        ];

        if ($this->isMethod('put'))
        {
            $required = [
                'name'         => 'sometimes|required|max:100',
                'country_code' => 'sometimes|required|string',
                'started_at'   => 'sometimes|required|date',
                'ended_at'     => 'sometimes|required|date',
                'page_url'     => 'required_with:published_at|string|unique:campaigns,page_url'
            ];
        }

        $optional = [
            'avatar_upload_id' => 'string|exists:uploads',
            'description'      => 'string|max:120',
            'published_at'     => 'date',
            'tags'             => 'array'
        ];

        return $rules = $required + $optional;
    }
}
