@extends('emails.layouts.default')

@section('content')
    <p>Hi {{ $user->name }}!</p>

    <h3>Thanks for joining Missions.Me.</h3>
    <p>The power to change lives around the world is now at your fingertips!</p>

    <h3>Get Started Now</h3>
    <p><button>Go on a Trip</button><br />Connect with others in need across the globe.</p>
    <p><button>Support a Cause</button><br />Contribute to a fundraiser or sponsor your own.</p>

    <p>Make sure your account looks sharp.</p>
    <p><button>Update My Profile</button></p>
@stop
