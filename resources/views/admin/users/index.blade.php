@extends('layouts.admin')

@section('content')

@include('admin.partials._nav_people')

@breadcrumbs(['links' => [
    'admin' => 'Dashboard',
    'active' => 'Users'
]])
@endbreadcrumbs

<hr class="divider inv">

<div class="container">
    <div class="col-xs-12">
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