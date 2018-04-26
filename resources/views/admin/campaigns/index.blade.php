@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12">
                <ul class="breadcrumb">
                    <li><a href="{{ url('/admin') }}">Dashboard</a></li>
                    <li class="active">Campaigns</li>
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

            <div class="row">
                <div class="col-xs-8">
                    <ul class="nav nav-tabs">
                        <li role="presentation" class="active"><a href="#active" data-toggle="tab"><i
                                        class="fa fa-bolt"></i> Active</a></li>
                        <li role="presentation"><a href="#archive" data-toggle="tab"><i class="fa fa-archive"></i> Archived</a>
                        </li>
                    </ul>
                </div>
                <div class="col-xs-4 text-right">
                    @can('create', \App\Models\v1\Campaign::class)
                    <hr class="divider inv sm">
                    <a href="/admin/campaigns/create" class="btn btn-primary btn-sm">
                        <i class="fa fa-plus icon-left"></i> New
                    </a>
                    @endcan
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="active">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <admin-campaigns-list type="current"></admin-campaigns-list>
                                </div><!-- end panel-body -->
                            </div><!-- end panel -->
                        </div>
                        <div role="tabpanel" class="tab-pane" id="archive">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <admin-campaigns-list type="archived"></admin-campaigns-list>
                                </div><!-- end panel-body -->
                            </div><!-- end panel -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection