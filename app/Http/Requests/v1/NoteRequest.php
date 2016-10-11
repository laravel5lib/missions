<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class NoteRequest extends FormRequest
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
            'subject' => 'required|string',
            'content' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'noteable_id' => 'required|string',
            'noteable_type' => 'required|in:reservations,groups,trips,projects,fundraisers,transactions,funds, users'
        ];
    }
}
