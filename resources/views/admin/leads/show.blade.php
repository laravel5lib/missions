@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        '/admin' => 'Dashboard',
        '/admin/leads' => 'Leads',
        'active' => 'Details'
    ]])
    @endbreadcrumbs

    <hr class="divider inv lg">
    <div class="container-fluid">

        <div class="row">
            <div class="col-xs-12 col-md-7 col-md-offset-1">
        
                @component('panel')
                    @slot('title')<h5>Details</h5> @endslot
                    @component('list-group', ['data' => $lead->content])
                    @endcomponent
                @endcomponent

                @component('panel')
                    @slot('title')<h5>Delete Lead</h5> @endslot
                    @slot('body')
                        <div class="alert alert-warning">
                            <div class="row">
                                <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                                <div class="col-xs-11">USE CAUTION! This is a destructive action that cannot be undone.</div>
                            </div>
                        </div>
                        <delete-form url="leads/{{ $lead->uuid }}" 
                                    redirect="/admin/leads"
                                    label="Type the word 'delete' to delete it"
                                    button="Delete"
                                    match-value="delete">
                        </delete-form>
                    @endslot
                @endcomponent

            </div>
            <div class="col-xs-12 col-md-3 col-md-offset-1 small">
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
                        <notes type="leads"
                            id="{{ $lead->uuid }}"
                            user_id="{{ auth()->user()->id }}"
                            :per_page="10"
                            :can-modify="{{ auth()->user()->can('modify-notes')?1:0 }}">
                        </notes>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tasks">
                        <todos type="leads"
                            id="{{ $lead->uuid }}"
                            user_id="{{ auth()->user()->id }}"
                            :can-modify="{{ auth()->user()->can('modify-todos')?1:0 }}">
                        </todos>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection