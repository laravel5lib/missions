@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8">
                <h3 class="hidden-xs">User Medical Credentials <small>&middot; Edit</small></h3>
                <h3 class="text-center visible-xs">User Medical Credentials<br><small>Edit</small></h3>
            </div>
            <div class="col-sm-4 text-right hidden-xs">
                <hr class="divider inv sm">
                <a onclick="window.history.back()" class="btn btn-primary"><i class="fa fa-chevron-left icon-left"></i> Back</a>
            </div>
            <div class="col-sm-4 text-center visible-xs">
                <a onclick="window.history.back()" class="btn btn-primary"><i class="fa fa-chevron-left icon-left"></i> Back</a>
                <hr class="divider inv sm">
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
<div class="container">
    <div class="row">
            <div class="col-xs-12">
                <medical-credential-create-update :is-update="true" id="{{ $id }}" :for-admin="true"></medical-credential-create-update>
            </div>
    </div>
</div>
<hr class="divider inv xlg">
@endsection