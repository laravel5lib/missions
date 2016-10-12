<?php

namespace App\Http\Requests\v1;

use App\Http\Requests\Request;

class ProjectRequest extends Request
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
            'project_package_id' => 'required|exists:project_packages,id',
            'rep_id' => 'required|exists:users,id',
            'sponsor_id' => 'required|string',
            'sponsor_type' => 'required|in:users,groups',
            'plaque_prefix' => 'required|in:in honor of, in memory of, sponsored by',
            'plaque_message' => 'required|string',
            'funded_at' => 'date',
            'launched_at' => 'date',
            'completed_at' => 'date',
            'costs' => 'required|array',
            'costs.*.id' => 'required|exists:costs,id',
            'costs.*.quantity' => 'required|numeric|min:1'
        ];
    }
}
