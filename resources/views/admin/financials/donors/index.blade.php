@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>Donors</h3>
                </div>
                <div class="col-sm-4">
                    <hr class="divider inv sm">
                    <a href="{{ url('admin/donors/create') }}" class="btn btn-primary pull-right">New <i class="fa fa-plus"></i></a>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                @include('admin.financials.partials._tabs')
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
                <admin-donors-list></admin-donors-list>
            </div><!-- end panel-body -->
        </div><!-- end panel -->
    </div>
@stop