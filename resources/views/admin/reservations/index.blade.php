@extends('admin.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>Reservations <small>All</small></h3>
                <hr>
                <admin-reservations-list></admin-reservations-list>
            </div>
        </div>
    </div>
@endsection