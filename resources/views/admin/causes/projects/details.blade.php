@extends('admin.causes.projects.show')

@section('tab')
    <project-editor id="{{ $project->id }}"></project-editor>
@endsection