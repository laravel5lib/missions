<?php

namespace App\Http\Requests\v1;

use Dingo\Api\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'name'                  => 'required|unique:projects,name,' . $this->route('projects'),
            'project_initiative_id' => 'required|exists:project_initiatives,id',
            'sponsor_id'            => 'required|string',
            'sponsor_type'          => 'required|in:users,groups',
            'plaque_prefix'         => 'required|in:in honor of,in memory of,sponsored by,on behalf of',
            'plaque_message'        => 'required|string',
            'funded_at'             => 'date',
            'costs'                 => 'array',
            'costs.*.id'            => 'required|exists:costs,id',
        ];
    }
}
