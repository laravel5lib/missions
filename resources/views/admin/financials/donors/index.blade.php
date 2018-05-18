@extends('layouts.admin')

@section('content')

@include('admin.partials._nav_people')

@breadcrumbs(['links' => [
    'admin' => 'Dashboard',
    'active' => 'Donors'
]])
@endbreadcrumbs

<hr class="divider inv">
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <admin-donors-list></admin-donors-list>
                </div><!-- end panel-body -->
            </div><!-- end panel -->
        </div>
    </div>
</div>
@stop