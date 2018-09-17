@inject('markdown', 'League\CommonMark\Converter')

@extends('layouts.admin')

@section('content')

    @breadcrumbs(['links' => [
        'admin' => 'Dashboard', 
        'admin/campaigns' => 'Campaigns', 
        'admin/campaigns/'.$campaign->id.'/trip-templates' => $campaign->name,
        'active' => $template->name
    ]])
    @endbreadcrumbs
    
<hr class="divider inv lg">

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            @component('panel')
                @slot('title')
                    <div class="row">
                        <div class="col-xs-8">
                            <h5>Details</h5>
                        </div>
                        <div class="col-xs-4 text-right">
                            <a class="btn btn-sm btn-default" href="{{ url('/admin/campaigns/'.$template->campaign_id.'/trip-templates/' . $template->id . '/edit')}}">
                                <strong><i class="fa fa-pencil"></i> Edit</strong>
                            </a>
                        </div>
                    </div>
                @endslot
                @component('list-group', ['data' => [
                    'Name' => $template->name,
                    'Country' => country($template->country_code),
                    'Type' => '<strong>'.ucfirst($template->type).'</strong>',
                    'Tags' => function() use($template) {
                        foreach($template->tags as $tag) {
                            echo '<span class="label label-filter">'.ucwords($tag->name).'</span>';
                        }
                    },
                    'Start Date' => $template->started_at->format('F d, Y'),
                    'End Date' => $template->ended_at->format('F d, Y'),
                    'Difficulty' => $template->difficulty,
                    'Spots Remaining' => $template->spots,
                    'Registration Closes' => ($template->closed_at ? $template->closed_at->format('F d, Y h:i a') : null),
                    'Roles Available' => function() use($template) {
                        foreach($template->team_roles as $role) {
                            echo '<span class="label label-filter">'.teamRole($role).'</span> ';
                        }
                    },
                    'Ideal for' => function() use($template) {
                        foreach($template->prospects as $prospect) {
                            echo '<span class="label label-filter">'.ucwords($prospect).'</span> ';
                        }
                    },
                    'Default Companion Limit' => $template->companion_limit,
                    'Created' => '<datetime-formatted value="'.$template->created_at->toIso8601String().'" />',
                    'Last Updated' => '<datetime-formatted value="'.$template->updated_at->toIso8601String().'" />'
                ]])
                @endcomponent
            @endcomponent

            @component('panel')
                @slot('title')
                    <h5>Public Page</h5>
                @endslot
                @slot('body')
                    {!! $markdown->convertToHtml($template->description) !!}
                @endslot
            @endcomponent

            @component('panel')
                @slot('title')
                    <h5>Delete Template</h5>
                @endslot
                @slot('body')
                    <div class="alert alert-warning">
                        <div class="row">
                            <div class="col-xs-1 text-center"><i class="fa fa-exclamation-circle fa-lg"></i></div>
                            <div class="col-xs-11">USE CAUTION! This is a destructive action that cannot be undone.</div>
                        </div>
                    </div>
                    <delete-form url="campaigns/{{ $campaign->id }}/trip-templates/{{ $template->id }}" 
                                redirect="/admin/campaigns/{{ $campaign->id }}/trip-templates" 
                                label="Enter the name to delete it" 
                                match-value="{{ ucfirst($template->name) }}">
                    </delete-form>
                @endslot
            @endcomponent

        </div>
    </div>
</div>

@endsection