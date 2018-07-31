@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 
        'admin/campaigns' => 'Campaigns', 
        'admin/campaigns/'.$campaign->id.'/requirements' => $campaign->name,
        'active' => $requirement->name
    ]])
    @endbreadcrumbs
    
    <hr class="divider inv lg">

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-8">
                @component('panel')
                    @slot('title')
                        <div class="row">
                            <div class="col-xs-6">
                                <h5>Usage</h5>
                            </div>
                            <div class="col-xs-6 text-right">
                                <button class="btn btn-sm btn-default" data-toggle="modal" data-target="#addOnDemand">Add to existing</button>
                            </div>
                        </div>
                    @endslot

                    @slot('body')
                        <div class="row">
                            <div class="col-md-3">
                                <h4 class="text-primary">
                                    {{ $requirement->groups_count }} <span class="text-muted">/ {{ $campaign->groups_count }}</span>
                                </h4>
                                <p class="small text-muted"><strong>Groups</strong></p>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-primary">
                                    {{ $requirement->trips_count }} <span class="text-muted">/ {{ $campaign->trips_count }}</span>
                                </h4>
                                <p class="small text-muted"><strong>Trips</strong></p>
                            </div>
                            <div class="col-md-3">
                                <h4 class="text-primary">
                                    {{ $requirement->reservations_count }} <span class="text-muted">/ {{ $campaign->reservations_count }}</span>
                                </h4>
                                <p class="small text-muted"><strong>Reservations</strong></p>
                            </div>
                        </div>
                    @endslot
                @endcomponent


                @component('panel')
                    @slot('title')
                        <div class="row">
                            <div class="col-xs-8">
                                <h5>Details</h5>
                            </div>
                            <div class="col-xs-4 text-right">
                                <a href="{{ url('/admin/campaigns/'.$campaign->id.'/requirements/'.$requirement->id.'/edit') }}" 
                                   class="btn btn-default btn-sm"
                                >
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endslot
                    @include('admin.partials._requirement')
                @endcomponent

                @component('panel')
                    @slot('title')
                        <h5>Delete Requirement</h5>
                    @endslot
                    @slot('body')
                        <div class="alert alert-warning">
                            <div class="row">
                                <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                                <div class="col-xs-11">USE CAUTION! This is a destructive action that cannot be undone. The requirement will be deleted and removed from all groups, trips, and reservations it is assigned to.</div>
                            </div>
                        </div>
                        <delete-form url="campaigns/{{ $campaign->id }}/requirements/{{ $requirement->id }}" 
                                     redirect="/admin/campaigns/{{ $campaign->id }}/requirements"
                                     label="Enter the requirement name to delete it"
                                     match-value="{{ $requirement->name }}">
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
                        <notes type="requirements"
                            id="{{ $requirement->id }}"
                            user_id="{{ auth()->user()->id }}"
                            :per_page="10"
                            :can-modify="{{ auth()->user()->can('modify-notes')?1:0 }}">
                        </notes>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tasks">
                        <todos type="requirements"
                            id="{{ $requirement->id }}"
                            user_id="{{ auth()->user()->id }}"
                            :can-modify="{{ auth()->user()->can('modify-todos')?1:0 }}">
                        </todos>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <bulk-add-requirement-modal 
        id="addOnDemand" 
        url="campaigns/{{ $campaign->id }}/requirements/{{ $requirement->id }}/add" 
        :options="['groups', 'trips', 'reservations']"
    ></bulk-add-requirement-modal>

@endsection