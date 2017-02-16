@extends('admin.causes.projects.show')

@section('tab')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Payments Due</h5>
        </div>
        <div class="panel-body">
            <admin-project-dues id="{{ $project->id }}"></admin-project-dues>
        </div>
    </div>
@endsection