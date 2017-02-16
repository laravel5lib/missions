@extends('dashboard.projects.show')

@section('tab')
    <project-editor id="{{ $project->id }}"></project-editor>
@endsection