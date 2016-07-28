@extends('admin.layouts.default')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <h3>Groups <small>Edit</small></h3>
                <hr>
                <group-edit group-id="{{ $id }}"></group-edit>
            </div>
        </div>
    </div>
@endsection