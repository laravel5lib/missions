<?php

namespace App\Http\Requests;

use App\Models\v1\TripTemplate;
use Dingo\Api\Http\FormRequest;

class TripTemplateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', TripTemplate::class);
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
                'name'            => 'required|string|max:255',
                'country_code'    => 'required|country',
                'type'            => 'required|string',
                'difficulty'      => 'required|in:level_1,level_2,level_3',
                'started_at'      => 'required|date|before:ended_at',
                'ended_at'        => 'required|date|after:started_at',
                'team_roles'      => 'required|array',
                'spots'           => 'nullable|numeric',
                'todos'           => 'nullable|array',
                'prospects'       => 'nullable|array',
                'description'     => 'nullable|string',
                'companion_limit' => 'nullable|numeric',
                'closed_at'       => 'nullable|date|before:started_at',
                'tags'            => 'array'
            ];
        }

        return [
            
        ];
    }
}
