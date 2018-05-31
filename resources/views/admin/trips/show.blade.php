@extends('layouts.admin')

@section('content')
    @breadcrumbs(['links' => [
        'admin' => 'Dashboard',
        'admin/campaigns' => 'Campaigns',
        '/admin/campaigns/'.$trip->campaign->id.'/groups' => $trip->campaign->name.' - '.country($trip->country_code),
        '/admin/campaign-groups/'.$group->uuid.'/trips' => $trip->group->name,
        'active' => ucfirst($trip->type).' Trip'
    ]])
    @endbreadcrumbs
    
    <hr class="divider inv lg">
    <div class="container-fluid">
        <div class="row">          
            <div class="col-xs-12 col-md-2">
                @sidenav(['links' => $pageLinks])
                @endsidenav
            </div>
            
            <div class="col-xs-12 col-md-7">
                @include('admin.trips.tabs.'.$tab)
            </div>

            <div class="col-md-3 small">
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
                        <notes type="trips"
                            id="{{ $trip->id }}"
                            user_id="{{ auth()->user()->id }}"
                            :per_page="10">
                        </notes>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tasks">
                        <todos type="trips"
                            id="{{ $trip->id }}"
                            user_id="{{ auth()->user()->id }}">
                        </todos>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection