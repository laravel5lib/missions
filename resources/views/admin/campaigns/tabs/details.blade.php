@extends('admin.campaigns.show')

@section('tab')
<div>
    @component('panel')
        @slot('body')
            <div class="row">
                <div class="col-sm-3">
                    <h4 class="text-primary">{{ $campaign->groups()->count() }}</h4>
                    <p class="small text-muted">Groups</p>
                </div>
                <div class="col-sm-3">
                    <h4>{{ $campaign->trips()->count() }}</h4>
                    <p class="small text-muted">Trips</p>
                </div>
                <div class="col-sm-3">
                    <h4 class="text-primary">{{ $campaign->reservations()->count() }}</h4>
                    <p class="small text-muted">Reservations</p>
                </div>
                <div class="col-sm-3">
                    <h4>{{ $campaign->interests()->count() }}</h4>
                    <p class="small text-muted">Interests</p>
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
                    @can('update', \App\Models\v1\Campaign::class)
                        <a href="{{ url('admin/campaigns/'.$campaign->id.'/edit') }}"
                            class="btn btn-sm btn-default">
                            Edit
                        </a>
                    @endcan
                </div>
            </div>
        @endslot
        @component('list-group', ['data' => [
            'Name' => $campaign->name,
            'Country' => country($campaign->country_code),
            'Start Date' => $campaign->started_at->format('D, F j, Y'),
            'End Date' => $campaign->ended_at->format('D, F j, Y'),
            'Short Description' => $campaign->short_desc,
            'Published' => ($campaign->published_at ? '<datetime-formatted value="'.$campaign->published_at->toIso8601String().'" />' : 'Private'),
            'Created' => '<datetime-formatted value="'.$campaign->created_at->toIso8601String().'" />',
            'Last Updated' => '<datetime-formatted value="'.$campaign->updated_at->toIso8601String().'" />'
        ]])
        @endcomponent
    @endcomponent

    @component('panel')
        @slot('title')
            <h5>Public Page</h5>
        @endslot
        @slot('body')
            @if($campaign->slug && $campaign->page_src)
                <pre>{{ $campaign->isPublished() ? url($campaign->slug->url) : url($campaign->slug->url) . ' (unpublished)' }}</pre>  
            @else
                <pre>No public page</pre>
            @endif
        @endslot
    @endcomponent

    @component('panel')
        @slot('title')
            <h5>Delete Campaign</h5>
        @endslot
        @slot('body')
            <div class="alert alert-warning">
                <div class="row">
                    <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                    <div class="col-xs-11">USE CAUTION! This is a destructive action that cannot be undone. All associated groups will be removed, all trips deleted and reservations dropped.</div>
                </div>
            </div>
            <delete-form url="campaigns/{{ $campaign->id }}" 
                         redirect="/admin/campaigns"
                         label="Enter the campaign name to delete it"
                         match-value="{{ $campaign->name }}">
            </delete-form>
        @endslot
    @endcomponent
</div>
@endsection