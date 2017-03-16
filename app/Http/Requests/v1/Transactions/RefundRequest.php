<?php

namespace App\Http\Requests\v1\Transactions;

use Dingo\Api\Http\FormRequest;

class RefundRequest extends FormRequest
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
            'transaction_id' => 'required|exists:transactions,id',
            'reason' => 'required|string'
        ];
    }
}
