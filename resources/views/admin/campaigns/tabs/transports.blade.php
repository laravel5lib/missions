@extends('admin.campaigns.show')

@section('tab')
<div>
    <transports campaign-id="{{ $campaign->id }}"></transports>
</div>
@endsection