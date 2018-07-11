@extends('admin.campaigns.groups.show')

@section('tab')

<fetch-json url="campaign-groups/{{ $group->uuid }}/requirements" v-cloak>
    <div class="panel panel-default" 
         slot-scope="{ json:requirements, loading, pagination }" 
         style="border-top: 5px solid #f6323e"
    >
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-8">
                    <h5>Travel Requirements <span class="badge">@{{ pagination.total }}</span></h5>
                </div>
                <div class="col-xs-4 text-right">
                    <a href="{{ url('admin/campaign-groups/'.$group->uuid.'/requirements/create') }}" 
                       class="btn btn-sm btn-primary"
                    >Add New</a>
                </div>
            </div>
        </div>
        <div class="panel-body text-center" v-if="!loading && !requirements.length">
            <span class="lead">No Requirements</span>
            <p>Add a requirement to get started.</p>
        </div>
        <div class="table-responsive" v-else>
            <table class="table table-striped">
                <thead>
                    <tr class="active">
                        <th>#</th>
                        <th>Requirement</th>
                        <th class="text-right">Trips</th>
                        <th class="text-right">Reservations</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(requirement, index) in requirements">
                        <td class="text-muted">@{{ index + 1 }}</td>
                        <td>
                            <strong>
                                <a :href="'/admin/campaign-groups/{{ $group->uuid }}/requirements/' + `${requirement.id}`">
                                    @{{ requirement.name }} 
                                    <span class="label" style="background:#eee; color:#777" v-if="requirement.custom">Custom</span>
                                </a>
                            </strong>
                        </td>
                        <td class="text-right"><code>@{{ requirement.trips_count }}</code></td>
                        <td class="text-right"><code>@{{ requirement.reservations_count }}</code></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</fetch-json>

@endsection