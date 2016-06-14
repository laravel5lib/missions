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
        return [
            'group_id'                        => 'required|exists:groups,id',
            'campaign_id'                     => 'exists:campaigns,id',
            'rep_id'                          => 'exists:reps,id',
            'spots'                           => 'numeric',
            'country_code'                    => 'required|in:' . $this->getCountries(),
            'type'                            => 'required|in:full,media,medical,short',
            'difficulty'                      => 'required|in:level_1,level_2,level_3',
            'thumb_src'                       => 'image',
            'started_at'                      => 'required|date',
            'ended_at'                        => 'required|date',
            'costs'                           => 'sometimes|required|array',
            'costs.*.name'                    => 'required|string',
            'costs.*.description'             => 'string',
            'costs.*.active_at'               => 'required|date',
            'costs.*.amount'                  => 'required|numeric',
            'costs.*.type'                    => 'required|string',
            'costs.*.payments'                => 'sometimes|required|array',
            'costs.*.payments.*.amount_owed'  => 'requied|numeric',
            'costs.*.payments.*.percent_owed' => 'requied|numeric',
            'costs.*.payments.*.due_at'       => 'date',
            'costs.*.payments.*.upfront'      => 'boolean',
            'costs.*.payments.*.grace_period' => 'required|numeric',
            'todos'                           => 'array',
            'description'                     => 'string',
            'deadlines'                       => 'sometimes|required|array',
            'deadlines.*.name'                => 'required|string',
            'deadlines.*.date'                => 'required|date',
            'deadlines.*.grace_period'        => 'numeric',
            'deadlines.*.enforced'            => 'boolean',
            'published_at'                    => 'date',
            'companion_limit'                 => 'numeric',
            'requirements'                    => 'sometimes|required|array',
            'requirements.*.name'             => 'required|string',
            'requirements.*.resources'        => 'required|array',
            'requirements.*.due_at'           => 'required|date',
            'requirements.*.grace_period'     => 'numeric'
        ];
    }

    /**
     * Get allowable country codes
     *
     * @return string
     */
    private function getCountries()
    {
        $country_codes = Country::codes();

        if($this->has('campaign_id'))
        {
            $campaign = Campaign::findOrFail($this->get('campaign_id'));
            $country_codes = implode(',', array_values($campaign->countries));
        }

        return $country_codes;
    }
}
