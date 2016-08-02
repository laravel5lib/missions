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
        return [
            'file'   => 'required',
            'path'   => 'required|string',
            'name'   => 'string',
            'meta'   => 'array',
            'type'   => 'required|in:other,banner,file,avatar',
            'x_axis' => 'numeric',
            'y_axis' => 'numeric',
            'width'  => 'numeric',
            'height' => 'numeric'
        ];
    }
}
