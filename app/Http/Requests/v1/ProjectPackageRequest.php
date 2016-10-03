<?php

namespace App\Http\Requests\v1;

use App\Http\Requests\Request;

class ProjectPackageRequest extends Request
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
            'amount' => 'required|numeric',
            'currency_code' => 'alpha|min:3|max:3',
            'generate_dates' => 'boolean',
            'project_type_id' => 'required|exists:project_types,id'
        ];
    }
}
