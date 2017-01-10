@extends('site.layouts.default')

@section('content')
    <group-trip-wrapper campaign-id="{{ $campaign->id }}"
                        campaign-name="{{ $campaign->name }}">
    </group-trip-wrapper>
@endsection