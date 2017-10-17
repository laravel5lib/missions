<?php

namespace App\Http\Requests\v1;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\Request;

class PromocodeRequest extends FormRequest
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
            'promotional_id' => 'required|string|exists:promotionals,id',
            'code' => 'string|unique:promocodes,code,NULL,id,deleted_at,NULL',
            'quantity' => 'numeric|max:100'
        ];
    }
}
