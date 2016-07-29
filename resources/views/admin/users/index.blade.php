@extends('admin.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>Users <small>All</small></h3>
                <hr>
                <admin-users-list></admin-users-list>
            </div>
        </div>
    </div>
@endsection