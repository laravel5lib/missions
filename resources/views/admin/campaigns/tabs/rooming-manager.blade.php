@extends('admin.campaigns.show')

@section('tab')
<div>
    <rooming-wizard user-id="{{ Auth::user()->id }}" campaign-id="{{ $campaign->id }}"></rooming-wizard>
    {{--<regions-manager campaign-id="{{ $campaign->id }}" :campaign-country="{ name: '{{ country($campaign->country_code) }}', code: '{{ $campaign->country_code }}' }"></regions-manager>--}}
</div>
@endsection