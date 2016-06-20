@extends('site.layouts.default')

@section('content')
    <div class="container">
        <h3>{{$campaign->name}}</h3>
        {{--<campaign-groups id="{{ $campaign->id }}"></campaign-groups>--}}
        {{--<hr>--}}
        {{--<group-trips id="9fa518bb-2cdf-4b41-8546-6c6eeaed24b2"></group-trips>--}}
        <group-trip-wrapper campaign-id="{{ $campaign->id }}"></group-trip-wrapper>
    </div>
@endsection