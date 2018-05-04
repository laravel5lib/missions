@extends('dashboard.layouts.default')

@section('content')
    <hr class="divider inv lg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3">
                @include('dashboard.reservations.layouts.menu', [
                'links' => config('navigation.dashboard.reservation'),
                'rep' => $rep
                ])
            </div>
            <div class="col-sm-7">
                @yield('tab')
            </div>
        </div>
    </div>
    <hr class="divider inv xlg">
@stop