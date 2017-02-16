@extends('dashboard.projects.show')

@section('tab')
    <project-dues id="{{ $project->id }}"></project-dues>
@endsection