<?php

namespace App\Http\Requests\v1;

use App\Http\Requests\Request;

class PassengerRequest extends Request
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
            'reservation_id' => 'required|exists:reservations,id',
            'transport_id'   => 'required|exists:transports,id',
            'seat_no'        => 'string'
        ];
    }
}
