@extends('admin.causes.projects.show')

@section('tab')
    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Applied Costs</h5>
        </div>
        <div class="panel-body">
            <cost-manager id="{{ $project->id }}" assignment="projects"></cost-manager>
        </div>
    </div>
@endsection