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
        'admin/records/passports' => 'Travel Documents',
        'active' => 'Passports'
    ]])
    @endbreadcrumbs

    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Passport</h5>
                    </div>
                    <div class="panel-body">
                        <passport-create-update 
                            :for-admin="true" 
                            reservation-id="{{ request()->get('reservation') }}" 
                            requirement-id="{{ request()->get('requirement') }}"
                        ></passport-create-update>
                    </div><!-- end panel-body -->
                </div><!-- end panel -->
            </div>
        </div>
    </div>
    <hr class="divider inv xlg">
@endsection