<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class CreateFlightItineraryRequest extends FormRequest
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
            'record_locator' => 'required|string',
            'type' => 'required|string',
            'flights' => 'required|array',
            'flights.*.flight_no' => 'required|string|max:60',
            'flights.*.date' => 'required|date',
            'flights.*.time' => 'required',
            'flights.*.iata_code' => 'required|max:10'
        ];
    }
}
