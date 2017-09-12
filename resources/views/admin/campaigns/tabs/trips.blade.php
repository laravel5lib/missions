@extends('admin.campaigns.show')

@section('tab')
<div>
    <admin-campaign-trips campaign-id="{{ $campaign->id }}"></admin-campaign-trips>
</div>
@endsection