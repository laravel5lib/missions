@extends('dashboard.layouts.default')

@section('content')
    @breadcrumbs(['links' => [
        'dashboard' => 'Dashboard',
        '/dashboard/records/'.$tab => ucwords(str_replace("-", " ", $tab)),
        'active' => 'Edit'
    ]])
    @endbreadcrumbs
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <travel-document-form 
                    id="{{ $id }}"
                    type="{{ $tab }}"
                    :is-update="true"
                    reservation-id="{{ request()->get('reservation') }}" 
                    requirement-id="{{ request()->get('requirement') }}"
                ></travel-document-form>
                <hr class="divider inv lg">
            </div>
        </div>
    </div>
@endsection