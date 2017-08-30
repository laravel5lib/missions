<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class PassengerRequest extends FormRequest
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
            'reservation_id' => 'required|string|exists:reservations,id',
            'transport_id'   => 'sometimes|required|exists:transports,id',
        ];

        if ($this->isMethod('put'))
        {
            $required = [
                'reservation_id' => 'sometimes|required|string|exists:reservations,id',
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
