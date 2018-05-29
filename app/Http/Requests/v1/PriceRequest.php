<?php

namespace App\Http\Requests\v1;

use App\Models\v1\Cost;
use App\Models\v1\Price;
use Dingo\Api\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PriceRequest extends FormRequest
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
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            // if (!$this->input('cost_id') and !$this->input('price_id')) {
            //     return;
            // }

            if($this->assertCustomPrice()) {
                
                if ($this->assertRequiredPayments()) {
                    $validator->errors()->add('payments', 'At least one payment is required.');
                }

                if ($this->assertMissingPaymentForFullAmount()) {
                    $validator->errors()->add('payments', 'A single payment for 100% due is required.');
                }

                if ($this->assertPaymentDatesAreInvalid()) {
                    $validator->errors()->add('payments', 'Payment dates are invalid.');
                }

                if ( ! $this->assertUniqueCost()) {
                    $validator->errors()->add('cost_id', 'This cost has already been used.');
                }

                if ($this->assertActivationDateRequired()) {
                    $validator->errors()->add('active_at', 'An effective date is required.');
                }

            } else {

                if ( ! $this->assertUniquePrice()) {
                    $validator->errors()->add('price_id', 'This price has already been added.');
                }

                // if ( ! $this->assertHasOneIncrementalCost()) {
                //     $validator->errors()->add('price_id', 'Only one incrementing cost can be added.');
                // }

            }

        });
    }
    
    /**
     * Assert if price is custom.
     *
     * @return boolean
     */
    private function assertCustomPrice()
    {
        $priceUuid = $this->route('price');

        if ($priceUuid) {
            $price = Price::whereUuid($priceUuid)->firstOrFail();

            return $price->model_id == $this->segment(3);
        }

        return !$this->input('price_id');
    }

    /**
     * Assert if payments are required.
     *
     * @return boolean
     */
    private function assertRequiredPayments()
    {
        if ($this->isForIncrementalCost()) {
            return $this->requestHasPayments() ? false : true;
        }

        return false;
    }

    /**
     * Assert that only one payment covers the full amount.
     *
     * @return boolean
     */
    private function assertMissingPaymentForFullAmount()
    {
        // this rule only applies if the request has payments
        if ( ! $this->requestHasPayments()) return false;

        return $this->assertOnlyOnePaymentHasPercentageValue(100) ? false : true;
    }

    /**
     * Assert payment dates are invalid.
     *
     * @return boolean
     */
    private function assertPaymentDatesAreInvalid()
    {
        // this rule only applies if the request has payments
        if ( ! $this->requestHasPayments()) return false;

        $payments = $this->input('payments');

        // first we create a collection of payments sorted by their percentages
        $paymentsByPercentage = collect($payments)->sortBy('percentage_due')->pluck('percentage_due');
        // second we create a collection of payments sorted by their due dates
        $paymentsByDate = collect($payments)->sortBy('due_at')->pluck('percentage_due');
        // next we compare the collections and look for mismatches
        $diff = $paymentsByPercentage->diffAssoc($paymentsByDate);
        // if the collections don't match then the dates are invalid
        return ! empty($diff->all());
    }

    /**
     * Assert unique cost.
     *
     * @return boolean
     */
    private function assertUniqueCost()
    {
        $price = DB::table('prices')
            ->join('priceables', function ($join) {
                $join->on('prices.id', '=', 'priceables.price_id')
                     ->where('priceable_type', $this->segment(2))
                     ->where('priceable_id', $this->segment(3));
            })
            ->where('cost_id', $this->input('cost_id'))
            ->first();

        return is_null($price) ?: false;
    }

    /**
     * Assert that an activation date is required.
     *
     * @return boolean
     */
    private function assertActivationDateRequired()
    {
        if ($this->input('active_at') or ! $this->input('cost_id')) return false;

        $cost = Cost::findOrFail($this->input('cost_id'));

        if ($cost->type === 'incremental' or $cost->type === 'fee') return true;
    }

    /**
     * Assert that only one payment has a percentage_due value of 100%
     *
     * @return boolean
     */
    private function assertOnlyOnePaymentHasPercentageValue($value)
    {
        return collect($this->input('payments'))->where('percentage_due', $value)->count() === 1;
    }

    /**
     * Check if the request already has payments
     *
     * @return boolean
     */
    private function requestHasPayments()
    {
        return ($this->filled('payments') && $this->input('payments') != []);
    }

    /**
     * Check if the request is for an incremental cost
     *
     * @return boolean
     */
    private function isForIncrementalCost()
    {
        $cost = $this->filled('cost_id') ? $this->getCostById() : $this->getCostFromPrice();

        return ($cost && $cost->type === 'incremental');
    }

    /**
     * Get cost by ID
     *
     * @return \App\Models\v1\Cost
     */
    private function getCostById()
    {
        return Cost::findOrFail($this->input('cost_id'));
    }

    /**
     * Get cost using a price ID
     *
     * @return \App\Models\v1\Cost
     */
    private function getCostFromPrice()
    {
        $priceId = $this->filled('price_id') ? $this->input('price_id') : $this->route('price');

        return Price::whereUuid($priceId)->firstOrFail()->cost;
    }

    /**
     * Assert that the price being added is unique
     *
     * @return boolean
     */
    private function assertUniquePrice()
    {
        $price = DB::table('prices')
            ->join('priceables', function ($join) {
                $join->on('prices.id', '=', 'priceables.price_id')
                     ->where('priceable_type', $this->segment(2))
                     ->where('priceable_id', $this->segment(3));
            })
            ->where('uuid', $this->input('price_id'))
            ->first();

        return is_null($price) ?: false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('put')) {
            return $this->updatePriceRules();
        }

        return $this->newPriceRules();
    }

    /**
     * Get rules for adding a new price
     *
     * @return array
     */
    private function newPriceRules()
    {
        return [
            'price_id'  => 'required_without:cost_id|exists:prices,uuid',
            'cost_id'   => [
                'required_without:price_id',
                'exists:costs,id',
                Rule::unique('prices')
                    ->where('model_type', $this->segment(2))
                    ->where('model_id', $this->segment(3))
            ],
            'active_at' => 'nullable|date',
            'amount'    => 'required_without:price_id|numeric',
            'payments'  => 'nullable|array',
            'payments.*.percentage_due' => 'required|numeric|min:1|max:100',
            'payments.*.due_at'         => 'required|date',
            'payments.*.grace_days'     => 'numeric|min:0'
        ];
    }

    /**
     * Get rules for updating a price.
     *
     * @return array
     */
    private function updatePriceRules()
    {
        return [
            'price_id'  => 'sometimes|required_without:cost_id|exists:prices,uuid',
            'cost_id'   => [
                'sometimes',
                'required_without:price_id',
                'exists:costs,id',
                Rule::unique('prices')
                    ->where('model_type', $this->segment(2))
                    ->where('model_id', $this->segment(3))
            ],
            'active_at' => 'nullable|date',
            'amount'    => 'sometimes|required_without:price_id|numeric',
            'payments'  => 'nullable|array',
            'payments.*.percentage_due' => 'required|numeric|min:1|max:100',
            'payments.*.due_at'         => 'required|date',
            'payments.*.grace_days'     => 'numeric|min:0'
        ];
    }

    /**
     * The error messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'price_id.required_without' => 'Please provide a price ID.',
            'price_id.exists' => 'Price ID is not valid.',
            'cost_id.required_without' => 'Please provide a cost ID.',
            'cost_id.exists' => 'Cost ID is not valid.',
            'cost_id.unique' => 'Cost has already been used.',
            'active_at.date' => 'Is not a valid date.',
            'amount.required_without' => 'Please enter an amount.',
            'amount.numeric' => 'Amount is not a valid format.',
            'payments.*.percentage_due.required' => 'Please enter a percentage due.',
            'payments.*.percentage_due.numeric' => 'Percentage due is invalid.',
            'payments.*.percentage_due.min' => 'Percentage due must be at least 1%.',
            'payments.*.percentage_due.max' => 'Percentage due cannot be more than 100%.',
            'payments.*.due_at.required' => 'Please provide a due date.',
            'payments.*.due_at.date' => 'Due date is invalid.',
            'payments.*.grace_days.numeric' => 'Grace days value is invalid.',
            'payments.*.grace_days.min' => 'Grace days must be a positive number.'
        ];
    }

}
