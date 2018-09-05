@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        '/admin/records/'.$tab => ucwords(str_replace("-", " ", $tab)),
        'active' => 'Details'
    ]])
    @endbreadcrumbs

    <hr class="divider inv lg">

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-9">
                <travel-document-detail 
                    id="{{ $id }}"
                    type="{{ $tab }}"
                    can-edit="{{ request()->user()->can('update', $document)}}"
                ></travel-document-detail>
                
                @can('delete', $document)
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
                                         redirect="/admin/records/{{ $tab }}"
                                         label='Type the word "delete" to remove it'
                                         button="Delete"
                                         match-value="delete">
                            </delete-form>
                        @endslot
                    @endcomponent    
                @endcan
                <hr class="divider inv lg">
            </div>
            <div class="col-xs-12 col-md-3 small">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active">
                        <a href="#notes" aria-controls="notes" role="tab" data-toggle="tab">Notes</a>
                    </li>
                    <li role="presentation">
                        <a href="#tasks" aria-controls="tasks" role="tab" data-toggle="tab">Tasks</a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="notes">
                        <notes type="{{ $tab }}"
                            id="{{ $id }}"
                            user_id="{{ auth()->user()->id }}"
                            :per_page="10"
                            :can-modify="{{ auth()->user()->can('modify-notes')?1:0 }}">
                        </notes>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tasks">
                        <todos type="{{ $tab }}"
                            id="{{ $id }}"
                            user_id="{{ auth()->user()->id }}"
                            :can-modify="{{ auth()->user()->can('modify-todos')?1:0 }}">
                        </todos>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection