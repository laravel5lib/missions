@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row hidden-xs">
                <div class="col-sm-8">
                    <h3>Project Causes</h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <a href="/admin/causes/create" class="btn btn-primary">New <i class="fa fa-plus"></i></a>
                </div>
            </div>
            <div class="row visible-xs">
                <div class="col-sm-8 text-center">
                    <h3>Project Causes</h3>
                </div>
                <div class="col-sm-4 text-center">
                    <a href="/admin/causes/create" class="btn btn-primary">New <i class="fa fa-plus"></i></a>
                    <hr class="divider inv sm">
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <project-causes></project-causes>
            </div>
        </div>
    </div>
@endsection