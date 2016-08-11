@extends('admin.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>Users <small>Edit</small></h3>
                <hr>
                <user-edit user-id="{{ $id }}"></user-edit>
            </div>
        </div>
    </div>
@endsection