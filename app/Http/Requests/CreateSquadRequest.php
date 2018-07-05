<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSquadRequest extends FormRequest
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
            'region_id' => 'required|exists:regions,id',
            'callsign' => [
                'required', 'string', 'max:60', 
                Rule::unique('squads')->where(function ($query) {
                    return $query->where('region_id', $this->region_id);
                })
            ]
        ];
    }
}
