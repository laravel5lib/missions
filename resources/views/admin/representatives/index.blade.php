@extends('layouts.admin')

@section('content')

@include('admin.partials._nav_people')

@breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'active' => 'Trip Reps'
    ]])
    @endbreadcrumbs

<hr class="divider inv">
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <representative-list></representative-list>
        </div>
    </div>
</div>
@endsection