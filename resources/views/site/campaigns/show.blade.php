@extends('site.layouts.default')

@section('content')
	@if($campaign->page_src)
		@include('site/campaigns/partials/' . $campaign->page_src)
	@else
		@include('site/campaigns/partials/_generic')
	@endif
	<group-trip-wrapper campaign-id="{{ $campaign->id }}" campaign-name="{{ $campaign->name }}"></group-trip-wrapper>
@endsection