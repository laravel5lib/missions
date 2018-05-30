<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class LeadRequest extends FormRequest
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
        $rules = [
            'category_id' => 'required|numeric'
        ];

        if ($this->input('category_id') === 1) {
            $rules = array_merge($rules, $this->organizationRules());
        }

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'category_id.required' => 'A category is required.',
            'category_id.numeric' => 'Invalid category ID.'
        ];

        if ($this->input('category_id') === 1) {
            $messages = array_merge($messages, $this->organizationMessages());
        }

        return $messages;
    }

    private function organizationRules()
    {
        return [
            'content.organization' => 'required|string',
            'content.type' => 'required|string',
            'content.country' => 'required|string',
            'content.address_one' => 'required|string',
            'content.address_two' => 'nullable|string',
            'content.city' => 'required|string',
            'content.state' => 'required|string',
            'content.zip' => 'required|string',
            'content.phone_one' => 'required|string',
            'content.phone_two' => 'nullable|string',
            'content.email' => 'required|email',
            'content.contact' => 'required|string',
            'content.position' => 'required|string',
            'content.spoke_with_rep' => 'required|boolean',
            'content.campaign_of_interest' => 'required|string'  
        ];
    }

    private function organizationMessages()
    {
        return [
            'content.organization.required' => 'Please enter an organization name.',
            'content.type.required' => 'Please choose a type.',
            'content.country.required' => 'Please select a country',
            'content.address_one.required' => 'Please enter an address.',
            'content.address_two.string' => 'Invalid address line.',
            'content.city.required' => 'Please enter a city.',
            'content.state.required' => 'Please enter a state or providence.',
            'content.zip.required' => 'Please enter a zip or postal code.',
            'content.phone_one.required' => 'Please enter a phone number.',
            'content.email.required' => 'Please enter a valid email.',
            'content.contact.required' => 'Please enter your name.',
            'content.position.required' => 'Please enter your position.',
            'content.campaign_of_interest.required' => 'Please choose a campaign.'  
        ];
    }
}
