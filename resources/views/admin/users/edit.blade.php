@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3>Edit Users</h3>
            </div>
            <div class="col-sm-4">
                <hr class="divider inv sm">
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <admin-user-edit user-id="{{ $id }}"></admin-user-edit>
            </div>
        </div>
    </div>
@endsection