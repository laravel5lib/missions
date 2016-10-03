@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row">
                <div class="col-sm-8">
                    <h3>Interests</h3>
                </div>
                <div class="col-sm-4">
                    <hr class="divider inv sm">
                    {{--<a href="/admin/interests" class="btn btn-primary pull-right">New <i class="fa fa-plus"></i></a>--}}
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <admin-interests-list></admin-interests-list>
            </div>
        </div>
    </div>
@endsection