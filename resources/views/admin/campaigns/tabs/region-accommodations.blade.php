@extends('admin.campaigns.show')
@inject('region', 'App\Models\v1\Region')
@inject('accommodation', 'App\Models\v1\Accommodation')

@section('tab')
<div>
    @if(Gate::allows('view', $region) && Gate::allows('view', $accommodation))
        <regions-accommodations campaign-id="{{ $campaign->id }}" ></regions-accommodations>
    @else
        <div class="text-center">
            @include('errors._403')
        </div>
    @endif
</div>
@endsection