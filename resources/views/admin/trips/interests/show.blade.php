@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        'admin/campaigns/'.$campaign->id => $campaign->name.' - '.country($campaign->country_code),
        'admin/campaigns/'.$campaign->id.'/reservations/interests' => 'Interests',
        'active' => 'Details'
    ]])
    @endbreadcrumbs

    <hr class="divider inv lg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 col-md-7 col-md-offset-1">
                @component('panel')
                    @slot('title')
                        <div class="row">
                            <div class="col-xs-6">
                                <h5>Details</h5>
                            </div>
                            <div class="col-xs-6 text-right">
                                <a href="{{ url('/admin/campaigns/'.$campaign->id.'/reservations/interests/'.$interest->id.'/edit') }}"
                                   class="btn btn-sm btn-default"
                                >
                                    Edit
                                </a>
                            </div>
                        </div>
                    @endslot
                    @component('list-group', ['data' => [
                        'status' => function() use ($interest) {
                            if ($interest->status === 'converted') {
                                echo '<i class="fa fa-check-circle text-success"></i><em>'.$interest->status.'</em>';
                            } elseif ($interest->status == 'declined') {
                                echo '<i class="fa fa-times-circle text-danger"></i><em>'.$interest->status.'</em>';
                            } else {
                                echo '<i class="fa fa-question-circle text-muted"></i><em>'.$interest->status.'</em>';
                            }
                        },
                        'name' => $interest->name,
                        'email' => '<strong><a href="mailto:'.$interest->email.'">'.$interest->email.'</a></strong>',
                        'phone' => '<strong><a href="tel:'.stripPhone($interest->phone).'">'.$interest->phone.'</a></strong>',
                        'prefers' => function () use ($interest) {
                            foreach($interest->communication_preferences as $value) {
                                echo '<span class="label label-default">'.ucfirst($value).'</span> ';
                            }
                        },
                        'trip_of_interest' => $interest->trip->campaign->name.'<br /><em>'.ucfirst($interest->trip->type).' Trip</em>',
                        'group' => '<strong><a href="'.url('/admin/organizations/'.$interest->trip->group_id).'">'.$interest->trip->group->name.'</a></strong>',
                        'Received' => '<datetime-formatted value="'.$interest->created_at->toIso8601String().'" />',
                        'Last Updated' => '<datetime-formatted value="'.$interest->updated_at->toIso8601String().'" />'
                    ]]) @endcomponent
                @endcomponent

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h5>Direct Link to Trip Registration Page</h5>
                    </div>
                    <div class="panel-body">
                        <pre style="overflow: scroll">{{ url('/trips/' . $interest->trip_id) }}</pre>
                        <span class="help-block">Copy & Paste this link to share with the interested party.</span>
                    </div>
                </div>

                @component('panel')
                    @slot('title')
                        <h5>Delete Trip Interest</h5>
                    @endslot
                    @slot('body')
                        <delete-form url="interests/{{ $interest->id }}" 
                                    redirect="/admin/campaigns/{{ $campaign->id }}/reservations/interests"
                                    label="Enter the interested party's email address to delete it"
                                    match-value="{{ $interest->email }}">
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
                        <notes type="trip_interests"
                            id="{{ $interest->id }}"
                            user_id="{{ auth()->user()->id }}"
                            :per_page="10"
                            :can-modify="{{ auth()->user()->can('modify-notes')?1:0 }}">
                        </notes>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tasks">
                        <todos type="trip_interests"
                            id="{{ $interest->id }}"
                            user_id="{{ auth()->user()->id }}"
                            :can-modify="{{ auth()->user()->can('modify-todos')?1:0 }}">
                        </todos>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection