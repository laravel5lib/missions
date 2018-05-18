@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'active' => 'Organizations'
    ]])
    @endbreadcrumbs

<hr class="divider inv lg">
<div class="container">
    <div class="row">
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-8">
                <!-- TAB NAVIGATION -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#tab1" role="tab" data-toggle="tab">Approved Groups</a></li>
                    <li><a href="#tab2" role="tab" data-toggle="tab">Pending Groups</a></li>
                </ul>
            </div>
            <div class="col-xs-4 text-right">
                @can('create', \App\Models\v1\Group::class)
                    <hr class="divider inv sm">
                    <a href="/admin/groups/create" class="btn btn-primary btn-sm"><i class="fa fa-plus icon-left"></i> New</a>
                @endcan
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="tab-content">
                    <div class="active tab-pane fade in" id="tab1">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <admin-groups></admin-groups>
                            </div><!-- panel-body -->
                        </div><!-- end panel -->
                    </div>
                    <div class="tab-pane fade" id="tab2">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <admin-groups :pending="true"></admin-groups>
                            </div><!-- panel-body -->
                        </div><!-- end panel -->
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
</div>

@endsection