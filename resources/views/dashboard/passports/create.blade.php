@extends('dashboard.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h1 class="text-muted text-center">My Passports <small>Create</small></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <passport-create-update></passport-create-update>
            </div>
            <div class="col-sm-4">
                <img src="/images/passport-placeholder.png" alt="" style="width: 100%;height: auto;">
            </div>
        </div>
    </div>
@endsection