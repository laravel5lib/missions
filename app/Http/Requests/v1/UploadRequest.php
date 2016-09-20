<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class UploadRequest extends FormRequest
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
        $rules = [
            'file'   => 'required_unless:type,video',
            'path'   => 'required_unless:type,video|string',
            'url'    => 'required_if:type,video',
            'name'   => 'string',
            'meta'   => 'array',
            'type'   => 'required|in:other,banner,file,avatar,video',
            'x_axis' => 'numeric',
            'y_axis' => 'numeric',
            'width'  => 'numeric',
            'height' => 'numeric',
            'tags'   => 'required|array',
            'tags.*' => 'in:Campaign,User,Group,Fundraiser'
        ];

        return $rules;
    }
}
