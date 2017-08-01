<?php

namespace App\Http\Requests\v1\Interaction;

use App\Http\Requests\Request;

class SiteRequest extends Request
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
            'id'              => 'string|size:36',
            'author_id'       => 'required|string|size:36',
            'author_type'     => 'required|in:reservations,translators',
            'region_id'       => 'required|exists:regions,id',
            'site_type'       => 'required|in:plaza,school,park,market,neighborhood,stadium,church,other',
            'call_sign'       => 'required|string',
            'total_reached'   => 'required|numeric',
            'total_decisions' => 'required|numeric',
            'lat'             => 'string',
            'long'            => 'string',
            'created_at'      => 'date',
            'updated_at'      => 'date'
        ];
    }
}
