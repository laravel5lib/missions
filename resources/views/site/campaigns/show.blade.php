@extends('site.layouts.default')

@section('content')
	@include('site/campaigns/partials/_1n1d2017')
	<group-trip-wrapper campaign-id="{{ $campaign->id }}" campaign-name="{{ $campaign->name }}"></group-trip-wrapper>
@endsection