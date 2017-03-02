@extends('admin.causes.projects.show')

@section('tab')
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-8">
                    <h5>Other Deadlines</h5>
                </div>
                <div class="col-xs-4 text-right">
                    <action-trigger text="New Deadline" icon="fa fa-plus" event="NewDeadline" size="btn-sm"></action-trigger>
                </div>
            </div>
        </div>
        <div class="panel-body">
            <deadlines-manager id="{{ $project->id }}" assignment="projects"></deadlines-manager>
        </div>
    </div>
@endsection