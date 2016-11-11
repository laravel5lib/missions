<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class EssayRequest extends FormRequest
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
            'author_name' => 'required|string',
            'subject' => 'required|string',
            'content' => 'required|array',
            'user_id' => 'required|exists:users,id'
        ];
    }
}
