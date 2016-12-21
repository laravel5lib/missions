@extends('dashboard.layouts.default')

@section('content')
    <div class="white-header-bg">
        <div class="container">
            <div class="row hidden-xs">
                <div class="col-sm-8">
                    <h3>{{ $project->name }}</h3>
                </div>
                <div class="col-sm-4 text-right">
                    <hr class="divider inv sm">
                    <a href="/admin/causes/{{ $project->initiative->project_cause_id }}/current-projects" class="btn btn-default">
                        <i class="fa fa-chevron-left icon-left"></i> Projects
                    </a>
                </div>
            </div>
            <div class="row visible-xs">
                <div class="col-sm-8 text-center">
                    <h3>{{ $project->name }}</h3>
                </div>
                <div class="col-sm-4 text-center">
                    <a href="/admin/causes/{{ $project->initiative->project_cause_id }}/current-projects" class="btn btn-default">
                        <i class="fa fa-chevron-left icon-left"></i> Projects
                    </a>
                    <hr class="divider inv sm">
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-4">

                @include('dashboard.projects._menu', [
                'links' => config('navigation.dashboard.project')
                ])
            </div>
            <div class="col-sm-8">
                @yield('tab')
            </div>
        </div>
    </div>
@endsection