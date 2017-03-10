<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class StoryRequest extends FormRequest
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
            'title' => 'required|string|max:120',
            'content' => 'required|string',
            'author_id' => 'required|string',
            'author_type' => 'required|string|in:users,groups,fundraisers',
            'publications' => 'required|array',
            'publications.*.type' => 'required|in:users,groups,fundraisers',
            'publications.*.id' => 'required|string'
        ];
    }
}
