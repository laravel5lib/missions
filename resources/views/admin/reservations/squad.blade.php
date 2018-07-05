@extends('admin.reservations.show')

@section('tab')

@component('panel')
    @slot('title')<h5>Squad Assignment</h5>@endslot
    @if($membership)
    <div class="list-group">
        <div class="list-group-item">
            <div class="row">
                <div class="col-xs-4 text-muted text-right">
                    Location
                </div>
                <div class="col-xs-8">
                    <strong><a href="{{ url('/admin/campaigns/'.$reservation->trip->campaign_id.'/regions/'.$membership->squad->region_id) }}">
                        {{ $membership->squad->region->name }}
                    </a></strong>
                </div>
            </div>
        </div>
        <div class="list-group-item">
            <div class="row">
                <div class="col-xs-4 text-muted text-right">
                    Squad
                </div>
                <div class="col-xs-8">
                    <strong><a href="{{ url('/admin/campaigns/'.$reservation->trip->campaign_id.'/reservations/squads/'.$membership->squad->uuid) }}">
                        {{ $membership->squad->callsign }}
                    </a></strong>
                </div>
            </div>
        </div>
        <div class="list-group-item">
            <div class="row">
                <div class="col-xs-4 text-muted text-right">
                    Group
                </div>
                <div class="col-xs-8">
                    {{ $membership->group }}
                </div>
            </div>
        </div>
        <div class="list-group-item">
            <div class="row">
                <div class="col-xs-4 text-muted text-right">
                    Role
                </div>
                <div class="col-xs-8">
                    {{ teamRole($reservation->desired_role) }}
                </div>
            </div>
        </div>
    </div>
    @else
        @slot('body')<p class="text-muted text-center">No squad assignment. @endslot
    @endif
@endcomponent

@include('partials.reservations.squad-leadership')

@include('partials.reservations.squad-group')

@endsection