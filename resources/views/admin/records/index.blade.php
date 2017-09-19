@extends('admin.layouts.default')

@section('content')

    @yield('header')
    <hr class="divider inv lg">

    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                @include('admin.records.layouts.menu', [
                'links' => config('navigation.admin.records')
                ])
            </div>
            <div class="col-sm-9">
                @yield('tab')
            </div>
        </div>
    </div>

@endsection