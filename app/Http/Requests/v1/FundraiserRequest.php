<?php

namespace App\Http\Requests\v1;

use App\Models\v1\Fundraiser;
use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;

class FundraiserRequest extends FormRequest
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
        $rules = [
            'name'             => 'required|string',
            'short_desc'       => 'nullable|string',
            'type'             => 'required|string',
            'url'              => 'required|string|unique:slugs|alpha_dash',
            'fund_id'          => 'required|exists:funds,id',
            'public'           => 'boolean',
            'description'      => 'nullable|string',
            'started_at'       => 'required|date|before:ended_at',
            'ended_at'         => 'required|date|after:started_at',
            'tags'             => 'array'
        ];

        if ($this->isMethod('put')) {
            $fundraiser = Fundraiser::whereUuid($this->route('fundraiser'))->first();

            $rules['name'] = 'sometimes|required|string';
            $rules['type'] = 'sometimes|required|string';
            $rules['url'] = [
                'sometimes', 'required', 'string',
                Rule::unique('slugs')->ignore(($fundraiser->slug ? $fundraiser->slug->id : null))
            ];
            $rules['description'] = 'sometimes|required|string';
            $rules['fund_id'] = 'sometimes|required|string';
            $rules['started_at'] = 'sometimes|required|date|before:ended_at';
            $rules['ended_at'] = 'sometimes|required|date|after:started_at';
        }

        return $rules;
    }
}
