@extends('emails.layouts.default')

@section('content')
    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">Click here to reset your password: <a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a></p>
@endsection
