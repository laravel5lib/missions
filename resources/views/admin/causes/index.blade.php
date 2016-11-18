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
                    <button data-toggle="modal" data-target="#causeEditor" class="btn btn-primary">
                        New <i class="fa fa-plus"></i>
                    </button>
                </div>
            </div>
            <div class="row visible-xs">
                <div class="col-sm-8 text-center">
                    <h3>Project Causes</h3>
                </div>
                <div class="col-sm-4 text-center">
                    <button data-toggle="modal" data-target="#causeEditor" class="btn btn-primary">
                        New <i class="fa fa-plus"></i>
                    </button>
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

    <!-- Create Cause Modal -->
    <div class="modal fade" id="causeEditor" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            {{--<div class="modal-content">--}}
                <cause-editor></cause-editor>
                {{--<div class="modal-header">--}}
                    {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>--}}
                    {{--<h4 class="modal-title" id="myModalLabel">Modal title</h4>--}}
                {{--</div>--}}
                {{--<div class="modal-body">--}}
                    {{--<cause-editor></cause-editor>--}}
                {{--</div>--}}
            {{--</div>--}}
        </div>
    </div>

@endsection