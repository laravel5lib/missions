<?php

namespace App\Http\Requests\v1;

use App\Utilities\v1\Country;
use Dingo\Api\Http\FormRequest;

class ProjectInitiativeRequest extends FormRequest
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
            'type'         => 'required|max:50',
            'country_code' => 'required|in:' . Country::codes(),
            'short_desc'   => 'required|max:255',
            'upload_id'    => 'nullable|exists:uploads,id',
            'active'       => 'nullable|boolean',
            'started_at'   => 'required|date_format:Y-m-d H:i:s',
            'ended_at'     => 'required|date_format:Y-m-d H:i:s'
        ];
    }
}
