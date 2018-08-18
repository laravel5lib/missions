@extends('dashboard.layouts.default')

@section('content')
    <hr class="divider inv lg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                @include('dashboard.reservations.layouts.menu', [
                'links' => config('navigation.dashboard.reservation'),
                'rep' => $rep
                ])
            </div>
            <div class="col-sm-7">
                @yield('tab')
            </div>
            <div class="col-sm-3">
                @include('dashboard.reservations._trip_overview')

                @component('panel')
                @slot('body')
                @if($rep)
                    <div style="display:inline-block;">
                        <img class="img-circle img-sm" src="{{ $rep->avatar_url }}" width="50" height="50">
                    </div>
                    <div style="display:inline-block;vertical-align:middle;margin:0 0 0 10px;">
                        <label style="margin-bottom:0px;font-size:10px;">Missions.Me Trip Rep</label>
                        <h5 style="margin:3px 0 6px;">{{ $rep->name }}</h5>
                        <p style="font-size:10px;margin-top:3px;"><i class="fa fa-phone"></i> <a href="tel:{{ $rep->phone }}">{{ $rep->phone }}</a> / <i class="fa fa-envelope"></i> <a href="mailto:{{ $rep->email }}">{{ $rep->email }}</a></p>
                    </div>
                    <hr class="divider">
                @endif

                @foreach($reservation->trip->group->managers as $manager)
                    <div style="display:inline-block;">
                        <img class="img-circle img-sm" src="{{ $manager->avatar_url }}" width="50" height="50">
                    </div>
                    <div style="display:inline-block;vertical-align:middle;margin:0 0 0 10px;">
                        <label style="margin-bottom:0px;font-size:10px;">Team Coordinator</label>
                        <h5 style="margin:3px 0 6px;">{{ $manager->name }}</h5>
                        <p style="font-size:10px;margin-top:3px;"><i class="fa fa-phone"></i> <a href="tel:{{ $manager->phone }}">{{ $manager->phone }}</a> / <i class="fa fa-envelope"></i> <a href="mailto:{{ $manager->email }}">{{ $manager->email }}</a></p>
                    </div>
                    <hr class="divider">
                @endforeach
                @endslot
                @endcomponent
            </div>
        </div>
    </div>
    <hr class="divider inv xlg">
@stop