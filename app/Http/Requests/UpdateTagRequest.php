<?php

namespace App\Http\Requests;

use Spatie\Tags\Tag;
use Dingo\Api\Http\FormRequest;

class UpdateTagRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // return $this->user->hasAnyRole('super_admin|admin'));
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
            'name' => [
                'sometimes', 'required', 'string', 
                function($attribute, $value, $fail) {
                    if ($this->isUnique($attribute, $value)) {
                        return $fail($attribute.' is already in use.');
                    }
                }
            ]
        ];
    }

    private function isUnique($attribute, $value)
    {
        return Tag::findFromString($value, $this->route('type'));
    }
}
