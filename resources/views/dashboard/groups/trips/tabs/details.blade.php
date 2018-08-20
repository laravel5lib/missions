@component('panel')
@slot('title')<h5>Missionaries <span class="badge">{{ $trip->reservations()->count() }}</span></h5>@endslot
@slot('body')
    <div class="row">
        <div class="col-sm-3">
            <h4 class="text-primary">{{ $trip->reservations()->deposited()->count() }}</h4>
            <p class="small text-muted">Deposit Only</p>
        </div>
        <div class="col-sm-3">
            <h4 class="text-primary">{{ $trip->reservations()->inProcess()->count() }}</h4>
            <p class="small text-muted">In Process</p>
        </div>
        <div class="col-sm-3">
            <h4 class="text-primary">{{ $trip->reservations()->funded()->count() }}</h4>
            <p class="small text-muted">Fully Funded</p>
        </div>
        <div class="col-sm-3">
            <h4 class="text-primary">{{ $trip->reservations()->ready()->count() }}</h4>
            <p class="small text-muted">Travel Ready</p>
        </div>
    </div>
@endslot
@endcomponent

@component('panel')
    @slot('title')
        <h5>Details</h5>
    @endslot
    @component('list-group', ['data' => [
        'Campaign' => $trip->campaign->name,
        'Country' => country($trip->country_code),
        'Type' => '<strong>'.ucfirst($trip->type).'</strong>',
        'Tags' => function() use($trip) {
            foreach($trip->tags as $tag) {
                echo '<span class="label label-filter">'.ucwords($tag->name).'</span>';
            }
        },
        'Start Date' => $trip->started_at->format('F d, Y'),
        'End Date' => $trip->ended_at->format('F d, Y'),
        'Difficulty' => $trip->difficulty,
        'Created' => '<datetime-formatted value="'.$trip->created_at->toIso8601String().'" />',
        'Last Updated' => '<datetime-formatted value="'.$trip->updated_at->toIso8601String().'" />'
    ]])
    @endcomponent
@endcomponent

@component('panel')
    @slot('title')
        <div class="row">
            <div class="col-xs-8">
                <h5>Registration Settings</h5>
            </div>
            <div class="col-xs-4 text-right">
            </div>
        </div>
    @endslot
    @component('list-group', ['data' => [
        'Current Status' => '<strong>'.ucfirst($trip->status).'</strong>',
        'Visibility' => ($trip->public ? 'Public' : 'Private'),
        'Spots Remaining' => $trip->spots,
        'Total Reservations' => $trip->reservations()->count(),
        'Registration Closes' => ($trip->closed_at ? $trip->closed_at->format('F d, Y h:i a') : null)
    ]])
    @endcomponent
@endcomponent

@component('panel')
    @slot('title')
        <h5>Public Page &amp; Registration</h5>
    @endslot
    @slot('body')
        <label>Public Page</label>
        @if($trip->isPublished())
            <pre><a href="{{ url('/trips/'.$trip->id) }}">{{ url('/trips/'.$trip->id) }}</a></pre>  
        @else
            <pre>{{ url('/trips/'.$trip->id) .'(unpublished)' }}</s></pre>  
        @endif
        @if($trip->public)
            <label>Registration Form</label>
            <pre><a href="{{ url('/trips/'.$trip->id.'/register') }}">{{ url('/trips/'.$trip->id.'/register') }}</a></pre>
            <span class="help-block">End-user will be prompted to log in.</span>
        @endif
    @endslot
@endcomponent
