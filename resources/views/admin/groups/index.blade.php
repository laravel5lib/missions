@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row hidden-xs">
            <div class="col-sm-8">
                <h3>Groups</h3>
            </div>
            @can('create', \App\Models\v1\Group::class)
            <div class="col-sm-4 text-right">
                <hr class="divider inv sm">
                <a href="/admin/groups/create" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> New</a>
            </div>
            @endcan
        </div>
        <div class="row visible-xs">
            <div class="col-sm-8 text-center">
                <h3>Groups</h3>
            </div>
            @can('create', \App\Models\v1\Group::class)
            <div class="col-sm-4 text-center">
                <a href="/admin/groups/create" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> New</a>
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
                <!-- TAB NAVIGATION -->
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active"><a href="#tab1" role="tab" data-toggle="tab">Approved Groups</a></li>
                    <li><a href="#tab2" role="tab" data-toggle="tab">Pending Groups</a></li>
                </ul>
                <!-- TAB CONTENT -->
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
@endsection