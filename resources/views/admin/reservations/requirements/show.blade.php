@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/reservations' => 'Reservations',
        'admin/reservations/'.$reservation->id => $reservation->name,
        'admin/reservations/'.$reservation->id.'/requirements' => 'Requirements',
        'active' => $requirement->name
    ]])
    @endbreadcrumbs
    
    <hr class="divider inv lg">

    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-8">
                <travel-document 
                    type="{{ $requirement->document_type }}" 
                    reservation-id="{{ $reservation->id }}"
                ></travel-document>
                
                @component('panel')
                    @slot('title')
                        <div class="row">
                            <div class="col-xs-8">
                                <h5>Details</h5>
                            </div>
                            <div class="col-xs-4 text-right">
                                @if($requirement->isCustom('reservations', $reservation->id))
                                <a href="{{ url('/admin/reservations/'.$reservation->id.'/requirements/'.$requirement->id.'/edit') }}" 
                                   class="btn btn-link btn-sm"
                                >
                                    Edit
                                </a>
                                @endif
                                <button class="btn btn-default btn-sm" 
                                        data-toggle="modal" 
                                        data-target="#changeStatusModal"
                                >
                                    Change Status
                                </button>
                            </div>
                        </div>
                    @endslot
                    @component('list-group', [
                        'data' => [
                            'Status' => requirementStatusLabel($requirement->pivot->status ?? 'incomplete'),
                            'Name' => $requirement->name,
                            'Short Description' => $requirement->short_desc,
                            'Document Type' => '<code>'.$requirement->document_type.'</code>',
                            'Due Date' => '<datetime-formatted value="'.$requirement->due_at->toIso8601String().'"></datetime-formatted>'
                        ]
                    ])
                    @endcomponent
                @endcomponent

                @component('panel')
                    @slot('title')
                        <h5>Remove Requirement</h5>
                    @endslot
                    @slot('body')
                        <div class="alert alert-warning">
                            <div class="row">
                                <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                                <div class="col-xs-11">USE CAUTION! This is a destructive action that cannot be undone. Any attached documents will also be removed.</div>
                            </div>
                        </div>
                        <delete-form url="reservations/{{ $reservation->id }}/requirements/{{ $requirement->id }}" 
                                     redirect="/admin/reservations/{{ $reservation->id }}/requirements"
                                     label="Enter the requirement name to remove it"
                                     button="Remove"
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

    <alert-error>
        <template slot="title">Oops!</template>
        <template slot="message">Unable to change the requirement status.</template>
    </alert-error>

    <alert-success :timer="1000" :reload="true">
        <template slot="title">Changes Saved!</template>
        <template slot="message">The requirement status was updated.</template>
    </alert-success>


    <div class="modal fade" tabindex="-1" role="dialog" id="changeStatusModal">
      <div class="modal-dialog" role="document">
        <ajax-form 
            method="put" 
            action="reservations/{{ $reservation->id }}/requirements/{{ $requirement->id }}" 
            :initial="{{ json_encode(['status' => $requirement->pivot->status ])}}"
        >
            <template slot-scope="{ form }">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Change Status</h4>
                  </div>
                  <div class="modal-body">
                    <input-select 
                        name="status" 
                        v-model="form.status" 
                        :options="{ 'incomplete': 'Incomplete', 'attention': 'Needs Attention', 'reviewing': 'Under Review', 'complete': 'Completed'}"
                    > 
                        <label slot="label">Status</label>
                    </input-select>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                  </div>
                </div>
            </template>
        </ajax-form>
      </div>
    </div>

@endsection