@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>Trips</h3>
                </div>
                <div class="col-sm-4">
                    <hr class="divider inv sm">
                    <a href="/admin/reservations/create" class="btn btn-primary pull-right">New <i class="fa fa-plus"></i></a>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <admin-trips-list></admin-trips-list>
            </div>
        </div>
    </div>
@endsection