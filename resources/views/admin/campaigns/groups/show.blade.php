@extends('layouts.admin')

@section('content')
@breadcrumbs(['links' => [
    'admin' => 'Dashboard', 
    'admin/campaigns' => 'Campaigns', 
    'admin/campaigns/'.$group->campaign_id.'/groups' => $group->campaign->name.' - '.country($group->campaign->country_code),
    'active' => $group->organization->name
]])
@endbreadcrumbs

    <hr class="divider inv lg">

    <div class="container-fluid">
        <div class="row">

            <div class="col-md-2">
                @sidenav(['links' => [
                    'admin/campaign-groups/'.$group->uuid => 'Overview',
                    'admin/campaign-groups/'.$group->uuid.'/prices' => 'Pricing',
                    'admin/campaign-groups/'.$group->uuid.'/requirements' => 'Requirements',
                    'admin/campaign-groups/'.$group->uuid.'/trips' => 'Trips'
                ]])
                @endsidenav
            </div>

            <div class="col-md-7">
                @yield('tab')
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
                        <notes type="campaign-groups"
                            id="{{ $group->uuid }}"
                            user_id="{{ auth()->user()->id }}"
                            :per_page="10">
                        </notes>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="tasks">
                        <todos type="campaign-groups"
                            id="{{ $group->uuid }}"
                            user_id="{{ auth()->user()->id }}">
                        </todos>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection