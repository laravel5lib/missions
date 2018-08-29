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
        'admin/records/essays' => 'Travel Documents',
        'active' => 'Essays'
    ]])
    @endbreadcrumbs
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <essay-create-update 
                            user-id="{{ auth()->user()->id }}" 
                            :for-admin="true"
                            reservation-id="{{ request()->get('reservation') }}" 
                            requirement-id="{{ request()->get('requirement') }}"
                        ></essay-create-update>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <hr class="divider inv xlg">
@endsection