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
        return true;
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
            'started_at'   => 'required|date|before:ended_at',
            'ended_at'     => 'required|date|after:started_at',
            'page_src'     => 'required_with:published_at|string',
            'page_url'     => 'required_with:published_at|string|unique:campaigns,page_url'
        ];

        if ($this->isMethod('put'))
        {
            $required = [
                'name'         => 'sometimes|required|max:100',
                'country_code' => 'sometimes|required|string',
                'started_at'   => 'sometimes|required|date|before:ended_at',
                'ended_at'     => 'sometimes|required|date|after:started_at',
                'page_src'     => 'required_with:published_at|string',
                'page_url'     => 'required_with:published_at|string|unique:campaigns,id,' . $this->route('campaigns')
            ];
        }

        $optional = [
            'avatar_upload_id' => 'string|exists:uploads,id,type,avatar',
            'banner_upload_id' => 'string|exists:uploads,id,type,banner',
            'description'      => 'string|max:120',
            'published_at'     => 'date',
            'tags'             => 'array',
        ];

        return $rules = $required + $optional;
    }
}
