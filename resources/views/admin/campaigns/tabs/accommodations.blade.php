@extends('admin.campaigns.show')

@section('tab')
<div>
    <accommodations campaign-id="{{ $campaign->id }}" ></accommodations>
</div>
@endsection