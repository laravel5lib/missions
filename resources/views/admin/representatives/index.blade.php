@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <ul class="breadcrumb">
                    <li><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="active">Trip Reps</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
<div class="container-fluid">
    <div class="col-xs-12 col-md-2">
        @include('admin.partials._toolbar')
    </div>
    <div class="col-xs-12 col-md-10">
        <representative-list></representative-list>
    </div>
</div>
@endsection