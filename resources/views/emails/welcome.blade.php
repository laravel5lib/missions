@extends('emails.layouts.default')

@section('content')
	<img style="display:block;max-width:100%;height:auto;border-radius:4px;margin-bottom:50px;" src="<?php echo $message->embed(public_path('images/emails/welcome-header.jpg')); ?>">
    <h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;text-transform:capitalize;text-align:center;">Hi {{ $user->name }}!</h3>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;text-align:center;"><span style="color:#3e3e3e;font-weight:bold;">Thanks for joining Missions.Me.</span></p>
    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;margin-bottom:25px;text-align:center;">The power to change lives around the world is now at your fingertips!</p>
    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;text-align:center;"><span style="color:#3e3e3e;font-weight:bold;">Get Started Now</span></p>
    
    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;text-align:center;">Connect with others in need across the globe.</p>
    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;text-align:center;"><a href="http://missions.dev/campaigns" style="color:#fff;margin:0;padding:15px 25px;background:#f6323e;display:inline-block;text-decoration:none;border-radius:4px;font-weight:bold;margin-bottom:15px;">Go on a Trip</a>
    </p>
    
    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;text-align:center;">Contribute to a fundraiser or sponsor your own.</p>
    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;text-align:center;"><a href="http://missions.dev/donate" style="color:#fff;margin:0;padding:15px 25px;background:#f6323e;display:inline-block;text-decoration:none;border-radius:4px;font-weight:bold;margin-bottom:15px;">Support a Cause</a>
    </p>
	
    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;text-align:center;">Make sure your account looks sharp.</p>
    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;text-align:center;"><a href="http://missions.dev/login" style="color:#fff;margin:0;padding:15px 25px;background:#f6323e;display:inline-block;text-decoration:none;border-radius:4px;font-weight:bold;">Update My Profile</a></p>
@stop
