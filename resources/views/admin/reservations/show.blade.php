@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-xs-8">
                <a href="#">
                    <h3>
                        <img class="av-left img-sm img-circle" style="width:100px; height:100px" src="{{ image($reservation->trip->campaign->getAvatar()->source . "?w=200") }}" alt="{{ $reservation->trip->campaign->name }}">
                        {{ $reservation->trip->campaign->name }}
                        <small>&middot; {{ country($reservation->trip->campaign->country_code) }}</small>
                    </h3>
                </a>
            </div>
            <div class="col-xs-4 text-right">
                <hr class="divider inv xs">
                <hr class="divider inv sm">
                <div class="btn-group" role="group">
                    <a href="{{ url('admin/reservations') }}" class="btn btn-primary-darker"><span class="fa fa-chevron-left icon-left"></span></a>
                    <a class="btn btn-primary" href="{{ $reservation->id }}/edit">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>
@if($reservation->deleted_at)
<div class="dark-bg-primary">
    <div class="container">
        <div class="col-sm-8 text-center">
            <hr class="divider inv sm">
            {{-- <hr class="divider inv sm"> --}}
            <h5>This reservation was dropped and is no longer active.</h5>
            <hr class="divider inv sm">
            {{-- <hr class="divider inv sm hidden-xs"> --}}
        </div>
        <div class="col-sm-4 text-center">
            <hr class="divider inv sm hidden-xs">
            <button class="btn btn-sm btn-white-hollow"><i class="fa fa-undo"></i> Restore</button>
            <hr class="divider inv sm">
        </div>
    </div><!-- end container -->
</div><!-- end dark-bg-primary -->
@endif
<hr class="divider inv lg">
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-4">
            @include('admin.reservations.layouts.menu', [
                'links' => config('navigation.admin.reservation'),
                'rep' => $reservation->rep ? $reservation->rep : $reservation->trip->rep
                ])
        </div>
        <div class="col-xs-12 col-sm-8">
            @yield('tab')
        </div>
    </div>
</div>
@endsection