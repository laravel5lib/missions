@extends('site.layouts.default')

@section('content')
    <div class="container">
        <h3>{{$campaign->name}}</h3>
        <campaign-groups id="{{ $campaign->id }}"></campaign-groups>
    </div>
@endsection