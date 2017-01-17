<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReferralsController extends Controller
{
    public function show($id)
    {
        try {
            $referral = $this->api->get('referrals/'. $id);
        } catch (Dingo\Api\Exception\InternalHttpException $e) {
            // We can get the response here to check the status code of the error or response body.
            $response = $e->getResponse();

            return $response;
        }

        return view('site.referrals.response')->with(compact('referral'));
    }
}
