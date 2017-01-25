<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class CompanionRequest extends FormRequest
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
        $reservation = $this->route('reservations');

        return [
            'companion_reservation_id' => 'required|exists:reservations,id,id,!'.$reservation.'|is_compatable:'.$reservation.'|unique:companions,companion_reservation_id,null,reservation_id,reservation_id,'.$reservation.'|within_companion_limit:'.$reservation,
            'relationship' => 'required|in:family,friend,spouse,guardian,dependent,other'
        ];
    }
}
