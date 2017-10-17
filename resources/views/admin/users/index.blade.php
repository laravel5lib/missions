@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row hidden-xs">
            <div class="col-sm-8">
                <h3>Users</h3>
            </div>
            @can('create', \App\Models\v1\User::class)
            <div class="col-sm-4 text-right">
                <hr class="divider inv sm">
                <a class="btn btn-primary" href="users/create"><i class="fa fa-plus icon-left"></i> New</a>
            </div>
            @endcan
        </div>
        <div class="row visible-xs">
            <div class="col-sm-8 text-center">
                <h3>Users</h3>
            </div>
            @can('create', \App\Models\v1\User::class)
            <div class="col-sm-4 text-center">
                <a href="users/create" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> New</a>
                <hr class="divider inv sm">
            </div>
            @endcan
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
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
@endsection