@extends('dashboard.reservations.show')

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
                    {{ $membership->squad->region->name }}
                </div>
            </div>
        </div>
        <div class="list-group-item">
            <div class="row">
                <div class="col-xs-4 text-muted text-right">
                    Squad
                </div>
                <div class="col-xs-8">
                    {{ $membership->squad->callsign }}
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