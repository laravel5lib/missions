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
            'country_code' => 'required|country',
            'type'         => 'required|string',
            'difficulty'   => 'required|in:level_1,level_2,level_3',
            'started_at'   => 'required|date|before:ended_at',
            'ended_at'     => 'required|date|after:started_at',
            'team_roles'   => 'required|array'
        ];

        if ($this->isMethod('put')) {
            $required = [
                'campaign_id'  => 'sometimes|required|exists:campaigns,id',
                'group_id'     => 'sometimes|required|exists:groups,id',
                'country_code' => 'sometimes|required|country',
                'type'         => 'sometimes|required|string',
                'difficulty'   => 'sometimes|required|in:level_1,level_2,level_3',
                'started_at'   => 'sometimes|required|date|before:ended_at',
                'ended_at'     => 'sometimes|required|date|after:started_at',
                'team_roles'   => 'sometimes|required|array',
            ];
        }

        $optional = [
            'rep_id'          => 'nullable|exists:representatives,id',
            'spots'           => 'nullable|numeric',
            'todos'           => 'nullable|array',
            'prospects'       => 'nullable|array',
            'description'     => 'nullable|string',
            'published_at'    => 'nullable|date',
            'companion_limit' => 'nullable|numeric',
            'closed_at'       => 'nullable|date|before:started_at',
            'public'          => 'boolean',
            'tags'            => 'array'
        ];

        $rules = $required + $optional;

        return $rules;
    }
}
