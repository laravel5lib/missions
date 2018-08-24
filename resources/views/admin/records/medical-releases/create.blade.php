@extends('layouts.admin')

@section('styles')
    <link rel="stylesheet" href="/css/slim.css" type="text/css">
@endsection
@section('scripts')
    <script src="/js/slim.js"></script>
@endsection

@section('content')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/records/medical-releases' => 'Travel Documents',
        'active' => 'Medial Releases'
    ]])
    @endbreadcrumbs
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <medical-create-update 
                    :for-admin="true"
                    reservation-id="{{ request()->get('reservation') }}" 
                    requirement-id="{{ request()->get('requirement') }}"
                ></medical-create-update>
            </div>
        </div>
    </div>
    <hr class="divider inv xlg">
@endsection