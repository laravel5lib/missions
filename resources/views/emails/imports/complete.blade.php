@extends('emails.layouts.default')

@section('content')

    <h3 style="color: #242424;font-family:Helvetica, Arial, sans-serif;color:#242424;">Hey!</h3>

    <p style="color: #242424;font-family:Helvetica, Arial, sans-serif;">You're import is completed. We've finished processing {{ $records }} {{ $list }}!</p>
@stop