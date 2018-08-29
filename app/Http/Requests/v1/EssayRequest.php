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
        if ($this->isMethod('post')) {
            return [
                'author_name'  => 'required|string',
                'content'      => 'required|array',
                'user_id'      => 'required|exists:users,id',
                'upload_ids'   => 'array',
                'upload_ids.*' => 'required|exists:uploads,id'
            ];
        }

        return [
            'author_name'  => 'sometimes|required|string',
            'content'      => 'sometimes|required|array',
            'user_id'      => 'sometimes|required|exists:users,id',
            'upload_ids'   => 'array',
            'upload_ids.*' => 'sometimes|required|exists:uploads,id'
        ];
    }
}
