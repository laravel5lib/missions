<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class CampaignGroupRequest extends FormRequest
{
    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->assertNotUniqueGroup()) {
                $validator->errors()->add('group_id', 'Group has already been added.');
            }
        });
    }

    private function assertNotUniqueGroup()
    {
        return DB::table('campaign_group')
            ->whereCampaignId($this->route('campaignId'))
            ->whereGroupId($this->input('group_id'))
            ->first();
    }

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
        if ($this->isMethod('put')) {
            return [
                'group_id' => 'sometimes|required|exists:groups,id',
                'status_id' => 'sometimes|required|numeric',
                'meta' => 'nullable|array'
            ];
        };

        return [
            'group_id' => 'required|exists:groups,id',
            'status_id' => 'required|numeric',
            'meta' => 'nullable|array'
        ];
    }

    public function messages()
    {
        return [
            'group_id.required' => 'Please select an organization.',
            'status_id.required' => 'Please choose a status',
            'meta.array' => 'Meta must be an array'
        ];
    }
}
