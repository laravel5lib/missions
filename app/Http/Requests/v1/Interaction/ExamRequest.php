<?php

namespace App\Http\Requests\v1\Interaction;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;

class ExamRequest extends FormRequest
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
            'id'             => 'string|size:32',
            'reservation_id' => 'exists:reservations',
            'region_id'      => 'required',
            'name'           => 'string',
            'gender'         => 'required|string|in:male,female',
            'age_group'      => 'required| string|in:child,youth,adult',
            'decision'       => 'required|boolean',
            'phone'          => 'string',
            'email'          => 'string|email',
            'lat'            => 'string',
            'long'           => 'string',
            'party_size'     => 'required|numeric|min:1',
            'treatments'     => 'required|array',
            'treatments.*'   => 'required|in:medical,medications,eye glasses,dental,other'
        ];
    }
}
