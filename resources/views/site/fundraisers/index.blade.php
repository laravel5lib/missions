@extends('site.layouts.default')

@section('content')
    <h1>All Fundraisers</h1>
    <ul>
        @foreach($fundraisers as $fundraiser)
            <li>
                <a href="/fundraisers/{{ $fundraiser->url }}">{{ $fundraiser->name }}</a>
            </li>
        @endforeach
    </ul>
@stop