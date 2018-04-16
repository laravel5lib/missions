<?php

namespace App\Http\Requests;

use App\Utilities\v1\Country;
use Dingo\Api\Http\FormRequest;

class TransactionRequest extends FormRequest
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
        switch ($this->request->get('type')) {
            case 'donation':
                return $this->donationRules();
                break;
            case 'transfer':
                return $this->transferRules();
                break;
            case 'refund':
                return $this->refundRules();
                break;
            case 'credit':
                return $this->creditRules();
                break;
            default:
                return $this->donationRules();
        }
    }

    public function donationRules()
    {
        $rules = [
            'anonymous'          => 'boolean',
            'amount'             => 'required|numeric',
            'description'        => 'nullable|string|max:120',
            'comment'            => 'nullable|string|max:120',
            'fund_id'            => 'required|string|exists:funds,id',
            'donor_id'           => 'required_without:donor|string|exists:donors,id',
            'donor'              => 'required_without:donor_id|array',
            'donor.first_name'   => 'required_with:donor|string|max:100',
            'donor.last_name'    => 'required_with:donor|string|max:100',
            'donor.company'      => 'nullable|string',
            'donor.email'        => 'required_with:donor|email',
            'donor.phone'        => 'nullable|string',
            'donor.zip'          => 'nullable|string',
            'donor.country_code' => 'nullable|in:' . Country::codes(),
            'donor.account_id'   => 'nullable|string',
            'donor.account_type' => 'nullable|in:users,groups',
            'details'            => 'required|array',
            'details.type'       => 'required|in:cash,check,card',
            'details.number'     => 'required_if:details.type,check|string',
            'token'              => 'string'
        ];

        if ($this->isMethod('put')) {
            $rules = [
                'amount'             => 'required|numeric',
                'description'        => 'nullable|string|max:120',
                'comment'            => 'nullable|string|max:120',
                'fund_id'            => 'required|string|exists:funds,id',
                'donor_id'           => 'required|string|exists:donors,id',
                'details'            => 'required|array',
                'details.type'       => 'required|in:cash,check,card',
                'details.number'     => 'required_if:details.type,check|string'
            ];
        }

        return $rules;
    }

    public function transferRules()
    {
        return [
            'amount' => 'required|numeric',
            'from_fund_id' => 'required|exists:funds,id',
            'to_fund_id' => 'required|exists:funds,id'
        ];
    }

    public function refundRules()
    {
        return [
            'amount' => 'required|numeric',
            'transaction_id' => 'required|exists:transactions,id',
            'reason' => 'required|string'
        ];
    }

    public function creditRules()
    {
        return [
            'amount'  => 'required|numeric',
            'fund_id' => 'required|exists:funds,id',
            'reason'  => 'required|string'
        ];
    }
}
