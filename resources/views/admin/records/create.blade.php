@extends('layouts.admin')

@section('content')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        '/admin/records/'.$tab => ucwords(str_replace("-", " ", $tab)),
        'active' => 'New'
    ]])
    @endbreadcrumbs
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <travel-document-form 
                    type="{{ $tab }}"
                    :for-admin="true"
                    reservation-id="{{ request()->get('reservation') }}" 
                    requirement-id="{{ request()->get('requirement') }}"
                ></travel-document-form>
                <hr class="divider inv lg">
            </div>
        </div>
    </div>
@endsection