@extends('admin.campaigns.show')

@section('tab')
<div>
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
        
    <visibility-controls campaign-id="{{ $campaign->id }}"></visibility-controls>
</div>
@endsection