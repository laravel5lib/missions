@extends('admin.campaigns.show')
@inject('accommodation', 'App\Models\v1\Accommodation')

@section('tab')
<div>
    @can('view', $accommodation)
        <rooming-accommodations campaign-id="{{ $campaign->id }}" ></rooming-accommodations>
    @else
        <div class="text-center">
            @include('errors._403')
        </div>
    @endcan
</div>
@endsection