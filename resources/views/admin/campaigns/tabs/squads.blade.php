@extends('admin.campaigns.show')
@inject('team', 'App\Models\v1\Team')

@section('tab')
<div>
    @can('view', $team)
        <team-manager user-id="{{ auth()->user()->id }}"
                      campaign-id="{{ $campaign->id }}">
        </team-manager>
    @else
        <div class="text-center">
            @include('errors._403')
        </div>
    @endcan
</div>
@endsection