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
            'started_at'   => 'required|date',
            'ended_at'     => 'required|date',
            'page_src'     => 'required_with:published_at|string',
            'page_url'     => 'required_with:published_at|string|unique:slugs,url'
        ];

        if ($this->isMethod('put')) {
            $required = [
                'name'         => 'sometimes|required|max:100',
                'country_code' => 'sometimes|required|string',
                'started_at'   => 'sometimes|required|date',
                'ended_at'     => 'sometimes|required|date',
                'page_src'     => 'required_with:published_at|string',
                'page_url'     => 'required_with:published_at|string|unique:slugs,url,'.$this->route('campaign').',slugable_id'
            ];
        }

        $optional = [
            'avatar_upload_id' => 'nullable|string|exists:uploads,id,type,avatar',
            'banner_upload_id' => 'nullable|string|exists:uploads,id,type,banner',
            'description'      => 'nullable|string|max:120',
            'published_at'     => 'nullable|date',
            'tags'             => 'nullable|array',
            'publish_squads'   => 'boolean',
            'publish_rooms'    => 'boolean',
            'publish_regions'  => 'boolean',
            'publish_transports' => 'boolean'
        ];

        return $rules = $required + $optional;
    }
}
