<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;

class ProjectCauseRequest extends FormRequest
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
            'name' => 'required|max:50',
            'short_desc' => 'max:255',
            'countries' => 'required|array',
            'upload_id' => 'exists:uploads,id'
        ];
    }
}
