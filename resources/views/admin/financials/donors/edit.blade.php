@extends('layouts.admin')

@section('content')
@breadcrumbs(['links' => [
    'admin' => 'Dashboard',
    'admin/donors' => 'Donors',
    'admin/donors/'.$donor->id => $donor->name,
    'active' => 'Edit'
]])
@endbreadcrumbs
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-2 col-sm-8">
                <donor-form :is-update="true" donor-id="{{ $donor->id }}"></donor-form>
            </div>
        </div>
    </div>
@stop