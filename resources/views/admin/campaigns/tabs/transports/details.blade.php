@extends('admin.campaigns.show')

@section('tab')
    <div>
        <transports-details campaign-id="{{ $campaign->id }}" transport-id="{{ $tabId }}"></transports-details>
    </div>
@endsection