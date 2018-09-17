@extends('admin.campaigns.show')

@section('tab')

<fetch-json url="campaigns/{{ $campaign->id }}/trip-templates" v-cloak>
    <div class="panel panel-default" 
         slot-scope="{ json:templates, loading, pagination }" 
         style="border-top: 5px solid #f6323e"
    >
        <div class="panel-heading">
            <div class="row">
                <div class="col-xs-8">
                    <h5>Trip Templates <span class="badge">@{{ pagination.total }}</span></h5>
                </div>
                <div class="col-xs-4 text-right">
                    <a href="{{ url('admin/campaigns/'.$campaign->id.'/trip-templates/create') }}" 
                       class="btn btn-sm btn-primary"
                    >Add New</a>
                </div>
            </div>
        </div>
        <div class="panel-body text-center" v-if="!loading && !templates.length">
            <span class="lead">No Templates</span>
            <p>Add a template to get started.</p>
        </div>
        <div class="table-responsive" v-else>
            <table class="table table-striped">
                <thead>
                    <tr class="active">
                        <th>#</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Tags</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(template, index) in templates">
                        <td class="text-muted">@{{ index + 1 }}</td>
                        <td>
                            <strong>
                                <a :href="`/admin/campaigns/${template.campaign_id}/trip-templates/${template.id}`">
                                    @{{ template.name }}
                                </a>
                            </strong>
                        </td>
                        <td><code>@{{ template.type }}</code></td>
                        <td><small><span class="label label-default" style="margin-right: 1em;" v-for="tag in template.tags">@{{ tag.name }}</span></small></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</fetch-json>

@endsection