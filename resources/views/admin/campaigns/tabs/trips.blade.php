@extends('admin.campaigns.show')
@inject('trip', 'App\Models\v1\Trip')

@section('tab')
<div>
    @can('view', $trip)
        <admin-campaign-trips campaign-id="{{ $campaign->id }}"></admin-campaign-trips>
    @else
        <div class="text-center">
            @include('errors._403')
        </div>
    @endcan
</div>
@endsection