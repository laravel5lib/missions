@extends('admin.layouts.default')

@section('content')
<div class="white-header-bg">
    <div class="container">
        <div class="row hidden-xs">
            <div class="col-sm-8">
                <h3>Uploads</h3>
            </div>
            <div class="col-sm-4 text-right">
                <hr class="divider inv sm">
                <a class="btn btn-primary" href="uploads/create"><i class="fa fa-plus icon-left"></i> New</a>
            </div>
        </div>
        <div class="row visible-xs">
            <div class="col-sm-8 text-center">
                <h3>Uploads</h3>
            </div>
            <div class="col-sm-4 text-center">
                <a class="btn btn-primary" href="uploads/create"><i class="fa fa-plus icon-left"></i> New</a>
                <hr class="divider inv sm">
            </div>
        </div>
    </div>
</div>
<hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <admin-uploads-list></admin-uploads-list>
                    </div><!-- panel-body -->
                </div><!-- end panel -->
            </div>
        </div>
    </div>
@endsection