<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class TripRequest extends FormRequest
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
            'campaign_id'  => 'required|exists:campaigns,id',
            'group_id'     => 'required|exists:groups,id',
            'country_code' => 'required',
            'type'         => 'required|in:ministry,family,international,leader,medical,media',
            'difficulty'   => 'required|in:level_1,level_2,level_3',
            'started_at'   => 'required|date',
            'ended_at'     => 'required|date',
            'closed_at'    => 'required|date',
        ];

        if ($this->isMethod('put'))
        {
            $required = [
                'campaign_id'  => 'sometimes|required|exists:campaigns,id',
                'group_id'     => 'sometimes|required|exists:groups,id',
                'country_code' => 'sometimes|required',
                'type'         => 'sometimes|required|in:ministry,family,international,leader,medical,media',
                'difficulty'   => 'sometimes|required|in:level_1,level_2,level_3',
                'started_at'   => 'sometimes|required|date',
                'ended_at'     => 'sometimes|required|date',
                'closed_at'    => 'sometimes|required|date',
            ];
        }

        $optional = [
            'rep_id'          => 'exists:users,id',
            'spots'           => 'numeric',
            'todos'           => 'array',
            'prospects'       => 'array',
            'description'     => 'string',
            'published_at'    => 'date',
            'companion_limit' => 'numeric',
            'facilitators'    => 'array',
            'tags'            => 'array'
        ];

        $rules = $required + $optional;

        return $rules;
    }
}
