@extends('admin.campaigns.show')

@section('tab')
<div>
    <admin-campaign-details campaign-id="{{ $campaign->id }}"></admin-campaign-details>
</div>
@endsection