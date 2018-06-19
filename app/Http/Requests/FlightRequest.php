<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class FlightRequest extends FormRequest
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
            'flight_itinerary_id' => 'required|exists:flight_itineraries,uuid',
            'flight_segment_id' => 'required|exists:flight_segments,uuid',
            'flight_no' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'iata_code' => 'required|max:10'
        ];
    }
}
