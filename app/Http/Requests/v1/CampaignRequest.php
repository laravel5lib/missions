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
            'ended_at'     => 'required|date|after:started_at'
        ];

        if ($this->isMethod('put')) {
            $required = [
                'name'         => 'sometimes|required|max:100',
                'country_code' => 'sometimes|required|string',
                'started_at'   => 'sometimes|required|date|before:ended_at',
                'ended_at'     => 'sometimes|required|date|after:started_at',
                'page_src'     => 'nullable|required_with:published_at|string',
                'page_url'     => 'nullable|required_with:published_at|string|unique:slugs,url,'.$this->route('campaign').',slugable_id'
            ];
        }

        $optional = [
            'description'      => 'nullable|string|max:120',
            'published_at'     => 'nullable|date',
            'publish_squads'   => 'boolean',
            'publish_rooms'    => 'boolean',
            'publish_regions'  => 'boolean',
            'publish_transports' => 'boolean'
        ];

        return $rules = $required + $optional;
    }

    public function messages()
    {
        return [
            'name.required'          => 'Please enter a campaign name.',
            'country_code.required'  => 'Please select a country.',
            'started_at.required'    => 'Please enter a start date.',
            'ended_at.required'      => 'Please enter an end date',
            'date'                   => 'That is not a valid date.',
            'started_at.before'      => 'The start date must be before the end date',
            'ended_at.after'         => 'The end date must be after the start date',
            'page_src.required_with' => 'A page source is required to publish.',
            'page_url.required_with' => 'A page url is required to publish.',
            'page_url.unique'        => 'This URL has already been taken.'
        ];
    }
}
