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
        $required = [
            'reservation_id' => 'required|exists:reservations,id',
            'transport_id'   => 'required|exists:transports,id',
        ];

        if ($this->isMethod('put'))
        {
            $required = [
                'reservation_id' => 'sometimes|required|exists:reservations,id',
                'transport_id'   => 'sometimes|required|exists:transports,id',
            ];
        }

        $optional = [
            'seat_no'        => 'string',
            'tags'           => 'array'
        ];

        return $rules = $required + $optional;
    }
}
