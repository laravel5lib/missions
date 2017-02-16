@extends('dashboard.projects.show')

@section('tab')
    <project-dues id="{{ $project->id }}"></project-dues>

    <project-costs id="{{ $project->id }}"></project-costs>
@endsection