@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row hidden-xs">
            <div class="col-sm-8">
                <h3>Campaigns</h3>
            </div>
            <div class="col-sm-4 text-right">
                <hr class="divider inv sm">
                @can('create', \App\Models\v1\Campaign::class)
                    <a href="/admin/campaigns/create" class="btn btn-primary">
                        <i class="fa fa-plus icon-left"></i> New
                    </a>
                @endcan
            </div>
        </div>
        <div class="row visible-xs">
            <div class="col-sm-8 text-center">
                <h3>Campaigns</h3>
            </div>
            <div class="col-sm-4 text-center">
                <a href="/admin/campaigns/create" class="btn btn-primary"><i class="fa fa-plus icon-left"></i> New</a>
                <hr class="divider inv sm">
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active"><a href="#active" data-toggle="tab"><i
                                    class="fa fa-bolt"></i> Active</a></li>
                    <li role="presentation"><a href="#archive" data-toggle="tab"><i class="fa fa-archive"></i> Archived</a>
                    </li>
                </ul>

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

@endsection