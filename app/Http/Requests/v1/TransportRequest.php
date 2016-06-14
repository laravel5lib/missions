<?php

namespace App\Http\Requests\v1;

use App\Http\Requests\Request;

class TransportRequest extends Request
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
            'type' => 'required|string|in:flight,bus,vehicle,other',
            'vessel_no' => 'required|string',
            'name' => 'required|string',
            'call_sign' => 'string',
            'domestic' => 'boolean',
            'capacity' => 'required|numeric',
            'campaign_id' => 'required|exists:campaigns,id',
            'departs_at' => 'date',
            'arrives_at' => 'date'
        ];
    }
}
