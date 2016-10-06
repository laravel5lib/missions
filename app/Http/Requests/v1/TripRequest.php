<?php

namespace App\Http\Requests\v1;

use App\Models\v1\Campaign;
use App\Utilities\v1\Country;
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
            'costs'                           => 'sometimes|required|array',
            'costs.*.name'                    => 'required|string',
            'costs.*.description'             => 'string',
            'costs.*.active_at'               => 'required|date',
            'costs.*.amount'                  => 'required|numeric',
            'costs.*.type'                    => 'required|string',
            'costs.*.payments'                => 'sometimes|required|array',
            'costs.*.payments.*.amount_owed'  => 'required|numeric',
            'costs.*.payments.*.percent_owed' => 'required|numeric',
            'costs.*.payments.*.due_at'       => 'date',
            'costs.*.payments.*.upfront'      => 'boolean',
            'costs.*.payments.*.grace_period' => 'required|numeric',
            'todos'                           => 'array',
            'prospects'                       => 'array',
            'description'                     => 'string',
            'deadlines'                       => 'array',
            'deadlines.*.name'                => 'required|string',
            'deadlines.*.date'                => 'required|date',
            'deadlines.*.grace_period'        => 'numeric',
            'deadlines.*.enforced'            => 'boolean',
            'published_at'                    => 'date',
            'companion_limit'                 => 'numeric',
            'requirements'                    => 'sometimes|required|array',
            'requirements.*.item'             => 'required|string',
            'requirements.*.due_at'           => 'required|date',
            'requirements.*.grace_period'     => 'numeric',
            'facilitators'                    => 'array',
            'tags'                            => 'array'
        ];

        $rules = $required + $optional;

        return $rules;
    }
}
