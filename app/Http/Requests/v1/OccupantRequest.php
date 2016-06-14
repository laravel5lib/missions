<?php

namespace App\Http\Requests\v1;

use App\Http\Requests\Request;

class OccupantRequest extends Request
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
            'room_no'           => 'required|string',
            'accommodation_id' => 'required|exists:accommodations,id',
            'reservation_id'   => 'required|exists:reservations,id',
            'room_leader'           => 'boolean'
        ];
    }
}
