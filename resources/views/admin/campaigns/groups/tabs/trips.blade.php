@extends('admin.campaigns.groups.show')

@section('tab')

<fetch-json url="/trips?include=tags" :parameters="{{ json_encode([
    'filter' => [
        'group_id' => $group->group_id,
        'campaign_id' => $group->campaign_id
    ]
])}}" v-cloak>
    <div class="panel panel-default" 
            style="border-top: 5px solid #f6323e" 
            slot-scope="{ json: trips, loading, pagination, changePage }"
    >
        <div class="panel-heading">
            <div class="row">
                <div class="col-sm-6">
                    <h5>Trips <span class="badge badge-default">@{{ pagination.total }}</span></h5>
                </div>
                <div class="col-xs-6 text-right">
                    <h5 v-if="loading" class="text-muted"><i class="fa fa-spinner fa-spin fa-fw"></i> Loading</h5>
                    <div class="btn-group btn-group-sm">
                        <button type="button" 
                                class="btn btn-primary dropdown-toggle" 
                                data-toggle="dropdown" 
                                aria-haspopup="true" 
                                aria-expanded="false"
                        >
                            Add New Trip <span class="caret"></span>
                        </button>
                      <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <a role="button" 
                               data-toggle="modal" 
                               data-target="#useTripTemplateModal"
                            >
                                New trip from template
                            </a>
                        </li>
                        <li role="separator" class="divider"></li>
                        <li>
                            <a href="{{ url('admin/campaign-groups/'.$group->uuid.'/trips/create') }}">
                                New custom trip
                            </a>
                        </li>
                      </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="table-responsive" v-if="trips && trips.length">
            <table class="table">
                <thead>
                    <tr class="active">
                        <th>#</th>
                        <th>Type</th>
                        <th>Dates</th>
                        <th>Reservations</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(trip, index) in trips">
                        <tr :key="trip.id">
                            <td rowspan="2">@{{ index+1 }}</td>
                            <td>
                                <strong><a :href="'/admin/trips/' + trip.id">@{{ trip.type | capitalize }}</a></strong>
                            </td>
                            <td class="col-sm-5">
                                @{{ trip.started_at | mFormat('MMM D', false, false) }} - @{{ trip.ended_at | mFormat('MMM D') }}
                            </td>
                            <td class="col-sm-1 text-right">
                                <strong>@{{ trip.reservations}}</strong> / @{{ trip.spots }}
                            </td>
                            <td>
                                @{{ trip.status | capitalize }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" style="border-top: none; padding-top: 0;">
                                <span class="label label-filter" 
                                      style="padding: 5px;"
                                      v-for="tag in trip.tags" 
                                      style="margin-right: 1em;">@{{ tag.name | capitalize }}</span>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <div class="panel-body text-center" v-else>
            <span class="lead">No Trips</span>
            <p>Create a trip for this group to get started.</p>
        </div>
        <div class="panel-footer" v-if="pagination.total > pagination.per_page">
            <pager :pagination="pagination" :callback="changePage"></pager>
        </div>
    </div>
</fetch-json>

<div class="modal fade" 
     tabindex="-1" 
     role="dialog" 
     id="useTripTemplateModal"
>
    <div class="modal-dialog" role="document">
        <ajax-form action="trip-templates/trips" 
                   method="post" 
                   :initial="{{ json_encode([
                        'template_id' => null, 
                        'rep_id' => null,
                        'group_id' => $group->group_id, 
                        'campaign_id' => $group->campaign_id,
                        'default_prices' => true,
                        'default_requirements' => true
                    ])}}"
        >
        <div class="modal-content" slot-scope="{ form }">
            <div class="modal-header">
                <button type="button" 
                        class="close" 
                        data-dismiss="modal" 
                        aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">New Trip From Template</h4>
            </div>
            <div class="modal-body">
                <input-select 
                    name="template_id" 
                    v-model="form.template_id" 
                    :options="{{ json_encode($templates) }}"
                >
                    <label slot="label">Choose Template</label>
                </input-select>
                <select-rep name="rep_id" v-model="form.rep_id">
                    <label slot="label">Default Trip Rep (optional)</label>
                    <span class="help-block" slot="help-text">
                        Search trip rep by entering an email. Select the rep to assign them.
                    </span>
                </select-rep>
                <div class="row">
                    <div class="col-sm-6">
                        <label>
                            <input type="checkbox" name="default_prices" v-model="form.default_prices">
                            Use Default Group Pricing
                        </label>
                        <span class="help-block">This will use the group's pricing as the trip's default pricing.</span>
                    </div>
                    <div class="col-sm-6">
                        <label>
                            <input type="checkbox" name="default_requirements" v-model="form.default_requirements">
                            Use Default Group Requirements
                        </label>
                        <span class="help-block">This will add the group's requirements as the default requirements for the trip.</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" 
                        class="btn btn-default" 
                        data-dismiss="modal"
                >
                    Close
                </button>
                <button type="submit" 
                        class="btn btn-primary"
                        :disabled="!form.template_id" 
                >
                    Use Template &amp; Create Trip
                </button>
            </div>
        </div>
        </ajax-form>
    </div>
</div>

<alert-error>
    <template slot="title">Oops!</template>
    <template slot="message">Something went wrong. We could not add a new trip.</template>
</alert-error>

<alert-success :reload="true" :timer="3000">
    <template slot="title">Nice Work!</template>
    <template slot="message">A new trip was added.</template>
</alert-success>

@endsection