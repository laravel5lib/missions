@extends('emails.layouts.default')

@section('content')
	<h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;text-align:center;">Donation Receipt</h3>

	<p style="color: #242424;font-family:Helvetica, Arial, sans-serif;text-align:center;line-height:24px;">Hey <span style="font-weight:bold;text-transform:capitalize;">{{ $donation->donor->name }}</span>, thank you for your generous donation of <span style="color:#05ce7b;font-weight:bold;">${{ $donation->amountInDollars() }}</span> to <span style="color:#3e3e3e;font-weight:bold;">{{ $donation->fund->name }}</span>. You're a direct part of changing lives around the world!</p>

	<p style="text-align:center;"><table style="border-collapse: collapse;font-size:12px;color: #242424;font-family:Helvetica, Arial, sans-serif;border: 2px solid #e6e6e6;width:100%;">
        <tbody>
            <tr>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;text-align:center;">Name Of Cause</td>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;text-align:center;">Donation Amount</td>
            </tr>
            <tr>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;font-weight:bold;"><span style="font-size:14px;color:#3e3e3e;font-weight:bold;">{{ $donation->fund->name }}</span></td>
                <td style="border: 2px solid #e6e6e6;padding:8px;vertical-align:middle;text-align:center;"><span style="font-size:20px;color:#05ce7b;font-weight:bold;">${{ $donation->amountInDollars() }}</span></td>
            </tr>
        </tbody>
    </table></p>

    <p style="font-size:12px;color: #242424;font-family:Helvetica, Arial, sans-serif;text-align:center;line-height:18px;">Please contact us for more information about the innovative international outreaches of Missions.Me. We pray you and your family will be blessed because of your generous giving.</p>
    <p style="font-size:12px;color: #242424;font-family:Helvetica, Arial, sans-serif;text-align:center;line-height:18px;">- Missions Me Team</p>

@stop