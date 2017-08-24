@extends('admin.campaigns.show')
@inject('region', 'App\Models\v1\Region')

@section('tab')
<div>
    @can('view', $region)
        <regions-manager campaign-id="{{ $campaign->id }}"
                         :campaign-country="{
                            name: '{{ country($campaign->country_code) }}',
                            code: '{{ $campaign->country_code }}'
                         }">
        </regions-manager>
    @else
        <div class="text-center">
            @include('errors._403')
        </div>
    @endcan
</div>
@endsection