@extends('layouts.admin')

@section('header')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/representatives' => 'Trip Reps',
        'active' => $representative->name
    ]])
    @endbreadcrumbs
@endsection

@section('content')
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <representative-edit-form :representative="{{ $representative }}"></representative-edit-form>
            </div>
        </div>
    </div>
@endsection