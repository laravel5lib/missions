@extends('dashboard.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-muted text-center">My Visas</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <visas-list></visas-list>
            </div>
        </div>
    </div>

@endsection