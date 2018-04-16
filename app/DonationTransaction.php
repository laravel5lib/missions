<?php
namespace App;

use App\Models\v1\Fund;
use Dingo\Api\Contract\Http\Request;
use App\Events\TransactionWasCreated;

class DonationTransaction extends TransactionHandler
{

    /**
     * Create a new donation.
     *
     * @param Request $request
     */
    public function create($request)
    {
        if ($request->get('details')['type'] == 'card') {
            // has a credit card token already been created and provided?
            // if not, tokenize the card details.
            $request->has('token') ?
                $token = $request->get('token') :
                $token = $this->merchant->createCardToken($request->get('card'));

            // create the charge with token, and donation details
            $charge = $this->merchant->createCharge($request->all(), $token);

            // merge description with request
            $fund = Fund::find($request->get('fund_id'));
            $request['description'] = 'Donation' . $fund ? 'toward '.$fund->name : null;

            // add metadata
            $request->merge(['metadata' => [
                'donor_name' => $request->get('donor')['name'],
                'donor_email' => isset($request->get('donor')['email']) ? $request->get('donor')['email'] : null,
                'donor_phone' => isset($request->get('donor')['phone']) ? $request->get('donor')['phone'] : null,
                'fund' => $request->get('fund_id')
            ]]);

            // capture the charge
            $this->merchant->captureCharge($charge['id']);

            // rebuild the payment array with new details
            $request->merge(['details' => [
                'type' => 'card',
                'comment' => $request->get('comment'),
                'charge_id' => $charge['id'],
                'brand' => $charge['source']['brand'],
                'last_four' => $charge['source']['last4'],
                'cardholder' => $charge['source']['name'],
            ]]);
        }

        // we can pass donor details to try and find a match
        // or to create a new donor if a match isn't found.
        $request->has('donor') ?
            $donor = $this->donor->firstOrCreate($request->get('donor')) :
            $donor = $this->donor->findOrFail($request->get('donor_id'));

        // Create the donation for the donor.
        $donation = $donor->donations()->create($request->all());

        event(new TransactionWasCreated($donation));
    }

    /**
     * Delete a donation.
     * @param $id
     */
    public function destroy($id)
    {
        $transaction = $this->transaction->findOrFail($id);
        $fund = $transaction->fund;

        // Refund the credit card
        if (isset($transaction->details['charge_id'])) {
            $this->merchant->refundCharge(
                $transaction->details['charge_id'],
                $transaction->amount
            );
        }

        $transaction->delete();

        $fund->reconcile();
    }
}
