<?php

namespace App\Http\Transformers\v1;

use App\Models\v1\Contact;
use League\Fractal\TransformerAbstract;

class ContactTransformer extends TransformerAbstract
{
    /**
     * Turn this item object into a generic array
     *
     * @param Contact $contact
     * @return array
     */
    public function transform(Contact $contact)
    {
        return [
            'id'             => $contact->id,
            'suffix'         => $contact->suffix,
            'first_name'     => $contact->first_name,
            'last_name'      => $contact->last_name,
            'email'          => $contact->email,
            'phone_one'      => $contact->phone_one,
            'phone_two'      => $contact->phone_two,
            'address_street' => $contact->address_street,
            'address_city'   => $contact->address_city,
            'address_state'  => $contact->address_state,
            'address_zip'    => $contact->address_zip,
            'country_code'   => $contact->country_code,
            'country_name'   => country($contact->country_code),
            'emergency'      => (bool) $contact->emergency,
            'relationship'   => $contact->relationship,
            'created_at'     => $contact->created_at->toDateTimeString(),
            'updated_at'     => $contact->updated_at->toDateTimeString(),
            'links'          => [
                [
                    'rel' => 'self',
                    'uri' => '/contacts/' . $contact->id,
                ]
            ],
        ];
    }

}