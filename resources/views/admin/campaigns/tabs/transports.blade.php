@extends('admin.campaigns.show')
@inject('transport', 'App\CampaignTransport');

@section('tab')
<div>
    @can('view', $transport)
        <transports campaign-id="{{ $campaign->id }}"></transports>
    @else
        <div class="text-center">
            @include('errors._403')
        </div>
    @endcan
</div>
@endsection