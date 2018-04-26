@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <ul class="breadcrumb">
                    <li><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="active">Users</li>
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
        @can('create', \App\Models\v1\User::class)
        <div class="text-right">
            <hr class="divider inv sm">
            <a class="btn btn-primary btn-sm" href="users/create"><i class="fa fa-plus icon-left"></i> New</a>
            <hr class="divider inv">
        </div>
        @endcan
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <admin-users-list></admin-users-list>
                    </div><!-- panel-body -->
                </div><!-- end panel -->
            </div>
        </div>
    </div>
</div>
@endsection