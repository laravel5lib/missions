@extends('admin.causes.projects.show')

@section('tab')
    <fund-editor id="{{ $project->fund->id }}" :read-only="true"></fund-editor>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h5>Transactions</h5>
        </div>
        <div class="panel-body">
            <admin-transactions-list fund="{{ $project->fund->id }}"></admin-transactions-list>
        </div>
    </div>
@endsection