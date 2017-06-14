@extends('admin.campaigns.show')

@section('tab')
<div>
    <regions-manager campaign-id="{{ $campaign->id }}" :campaign-country="{ name: '{{ country($campaign->country_code) }}', code: '{{ $campaign->country_code }}' }"></regions-manager>
</div>
@endsection