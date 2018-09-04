@extends('dashboard.layouts.default')

@section('content')
    @breadcrumbs(['links' => [
        'dashboard' => 'Dashboard',
        '/dashboard/records/'.$tab => ucwords($tab),
        'active' => 'Details'
    ]])
    @endbreadcrumbs
    <hr class="divider inv lg">
    <div class="container">
        <div class="row">
            <div class="col-md-offset-2 col-md-8">
                <travel-document-detail 
                    id="{{ $id }}"
                    type="{{ $tab }}"
                ></travel-document-detail>
                
                @component('panel')
                    @slot('title')
                        <h5>Delete Document</h5>
                    @endslot
                    @slot('body')
                        <div class="alert alert-warning">
                            <div class="row">
                                <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                                <div class="col-xs-11">USE CAUTION! This is a destructive action that cannot be undone. This will delete the document and detach it from any reservations using it.</div>
                            </div>
                        </div>
                        <delete-form url="{{ $tab }}/{{ $id }}" 
                                     redirect="/dashboard/records/{{ $tab }}"
                                     label='Type the word "delete" to remove it'
                                     button="Delete"
                                     match-value="delete">
                        </delete-form>
                    @endslot
                @endcomponent    

                <hr class="divider inv lg">
            </div>
        </div>
    </div>
@endsection