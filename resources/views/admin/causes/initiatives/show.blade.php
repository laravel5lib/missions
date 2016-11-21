@extends('admin.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row hidden-xs">
                <div class="col-sm-8">
                    <h3>{{ $initiative->type }}</h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <a href="/admin/causes/{{ $initiative->project_cause_id }}/current-initiatives" class="btn btn-default">
                        <i class="fa fa-chevron-left icon-left"></i> Initiatives
                    </a>
                </div>
            </div>
            <div class="row visible-xs">
                <div class="col-sm-8 text-center">
                    <h3>{{ $initiative->type }}</h3>
                </div>
                <div class="col-sm-4 text-center">
                    <a href="/admin/causes/{{ $initiative->project_cause_id }}/current-initiatives" class="btn btn-default">
                        <i class="fa fa-chevron-left icon-left"></i> Initiatives
                    </a>
                    <hr class="divider inv sm">
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
            <div class="row">
                <div class="col-md-offset-2 col-md-8">
                    <initiative-editor id="{{ $initiative->id }}"></initiative-editor>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-2 col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Pricing</h5>
                        </div>
                        <div class="panel-body">
                            {{--<admin-trip-costs id="{{ $initiative->id }}"--}}
                                              {{--assignment="project_initiatives">--}}
                            {{--</admin-trip-costs>--}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-offset-2 col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h5>Other Deadlines</h5>
                        </div>
                        <div class="panel-body">
                            {{--<admin-trip-deadlines id="05db003b-e3f8-4cee-a47d-08bc8846511c" assignment="trips"></admin-trip-deadlines>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection