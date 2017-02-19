@extends('emails.layouts.default')

@section('content')

    <h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;">Hey!</h3>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">Your exported report <strong>{{ $file }}</strong> has finished processing and is attached to this email.</p>
@stop