@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 
        'admin/campaigns' => 'Campaigns', 
        'admin/campaigns/'.$group->campaign_id.'/groups' => $group->campaign->name.' - '.country($group->campaign->country_code),
        'admin/campaign-groups/'.$group->uuid.'/requirements' => $group->organization->name,
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
                            <div class="col-xs-8">
                                <h5>Details</h5>
                            </div>
                            @if($requirement->isCustom('campaign-groups', $group->uuid))
                            <div class="col-xs-4 text-right">
                                <a href="{{ url('/admin/campaign-groups/'.$group->uuid.'/requirements/'.$requirement->id.'/edit') }}" 
                                   class="btn btn-default btn-sm"
                                >
                                    Edit
                                </a>
                            </div>
                            @endif
                        </div>
                    @endslot
                    @component('list-group', [
                        'data' => [
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
                                <div class="col-xs-11">USE CAUTION! This is a destructive action that cannot be undone. The requirement will be removed from the group and all trips and reservations it is assigned to.</div>
                            </div>
                        </div>
                        <delete-form url="requirements/{{ $requirement->id }}" 
                                     redirect="/admin/campaign-groups/{{ $group->uuid }}/requirements"
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

@endsection