@extends('admin.layouts.default')

@section('content')
@breadcrumbs(['links' => [
    'admin' => 'Dashboard',
    'admin/donors' => 'Donors',
    'active' => 'New'
]])
@endbreadcrumbs
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-offset-2 col-sm-8">
                <donor-form></donor-form>
            </div>
        </div>
    </div>
@stop