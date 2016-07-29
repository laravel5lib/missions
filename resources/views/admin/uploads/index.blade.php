@extends('admin.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>Uploads <small>All</small></h3>
                <hr>
                <admin-uploads-list></admin-uploads-list>
            </div>
        </div>
    </div>
@endsection