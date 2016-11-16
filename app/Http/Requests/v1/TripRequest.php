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
            'group_id'     => 'required|exists:groups,id',
            'country_code' => 'required',
            'type'         => 'required|in:ministry,family,international,leader,medical,media',
            'difficulty'   => 'required|in:level_1,level_2,level_3',
            'started_at'   => 'required|date',
            'ended_at'     => 'required|date',
        ];

        if ($this->isMethod('put'))
        {
            $required = [
                'group_id'     => 'sometimes|required|exists:groups,id',
                'country_code' => 'sometimes|required',
                'type'         => 'sometimes|required|in:ministry,family,international,leader,medical,media',
                'difficulty'   => 'sometimes|required|in:level_1,level_2,level_3',
                'started_at'   => 'sometimes|required|date',
                'ended_at'     => 'sometimes|required|date',
            ];
        }

        $optional = [
            'campaign_id'                     => 'exists:campaigns,id',
            'rep_id'                          => 'exists:reps,id',
            'spots'                           => 'numeric',
            'thumb_src'                       => 'image',
            'todos'                           => 'array',
            'prospects'                       => 'array',
            'description'                     => 'string',
            'published_at'                    => 'date',
            'companion_limit'                 => 'numeric',
            'facilitators'                    => 'array',
            'tags'                            => 'array'
        ];

        $rules = $required + $optional;

        return $rules;
    }
}
