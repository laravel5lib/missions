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
        return [
            'name' => 'required|max:100',
            'country_code' => 'required|string',
            'description' => 'string|max:120',
            'started_at' => 'required|date',
            'ended_at' => 'required|date',
            'published_at' => 'date',
            'page_url' => 'required_with:published_at|string|unique:campaigns,page_url'
        ];
    }
}
