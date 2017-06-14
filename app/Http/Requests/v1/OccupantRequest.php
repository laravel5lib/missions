<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class OccupantRequest extends FormRequest
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
        $rules =  [
            'reservation_id'   => 'required_without:reservations|exists:reservations,id',
            'reservations'     => 'array',
            'reservations.*'   => 'required|string',
            'room_leader'      => 'boolean'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'reservation_id'   => 'sometimes|required_without:reservations|exists:reservations,id',
                'reservations'     => 'array',
                'reservations.*'   => 'sometimes|required|string',
                'room_leader'      => 'required|boolean'
            ];
        }

        return $rules;
    }
}
